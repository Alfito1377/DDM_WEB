import re
from fastapi import FastAPI, BackgroundTasks, Depends, HTTPException
from pydantic import BaseModel
from sqlalchemy.orm import Session
from langchain_community.llms import Ollama
from langchain.prompts import PromptTemplate
from langchain_community.utilities import SQLDatabase

from fastapi import FastAPI, BackgroundTasks, Depends, HTTPException
from pydantic import BaseModel
import re

# Mengimpor modul yang sudah kita buat sebelumnya
from rag_engine import process_new_document, get_retriever

# IMPORT UNTUK DATABASE SQL LANGCHAIN
from langchain_community.llms import Ollama
from langchain.prompts import PromptTemplate
from langchain_community.utilities import SQLDatabase

# Inisialisasi Aplikasi API
app = FastAPI(title="Service AI Benih & Retur")

# Inisialisasi Model AI Lokal (Ollama)
# Jika nanti Anda beralih ke Gemini/Groq, cukup ganti 1 baris ini
llm = Ollama(model="qwen2.5:7b")

# --- KONFIGURASI DATABASE UNTUK AI (TEXT-TO-SQL) ---
DB_USER = "root"
DB_PASSWORD = ""
DB_HOST = "localhost"
DB_NAME = "SAGE" # PENTING: Ubah sesuai dengan nama database di .env Laravel Anda!

db_uri = f"mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}/{DB_NAME}"

# Kunci Kecepatan: Kita batasi AI HANYA boleh melihat 4 tabel ini
tabel_diizinkan = ["driver", "stores", "vehicle", "logistic"]
sql_db = SQLDatabase.from_uri(db_uri, include_tables=tabel_diizinkan)

# --- SKEMA REQUEST ---
class DocPayload(BaseModel):
    file_path: str

class ChatPayload(BaseModel):
    pertanyaan: str


# --- 1. WEBHOOK ENDPOINTS ---
@app.post("/webhook/document")
async def webhook_terima_dokumen(payload: DocPayload, background_tasks: BackgroundTasks):
    """Menerima lokasi file PDF/DOCX/XLSX baru dari Laravel dan memprosesnya ke Vector DB."""
    background_tasks.add_task(process_new_document, payload.file_path)
    return {"status": "success", "message": "Dokumen diterima dan sedang disisipkan ke ChromaDB."}


# --- 2. AGENTIC ROUTER (INTI CHATBOT) ---
@app.post("/chat")
async def chat_endpoint(payload: ChatPayload):
    pertanyaan = payload.pertanyaan
    
    # LANGKAH A: Router Berpikir (Klasifikasi Niat)
    # 1. PERBAIKI ROUTER PROMPT (Sesuaikan dengan istilah logistik/driver)
    router_prompt = PromptTemplate.from_template(
        "Tugas Anda adalah mengklasifikasikan pertanyaan user ke dalam 3 kategori. Anda HANYA BOLEH menjawab dengan angka 1, 2, atau 3.\n\n"
        "KATEGORI:\n"
        "1 = DATABASE (Pertanyaan seputar daftar driver, kendaraan/vehicle, logistik, toko/stores, status pengiriman, atau laporan data)\n"
        "2 = DOKUMEN (Pertanyaan seputar SOP, aturan kerja, panduan, atau teks dokumen)\n"
        "3 = UMUM (Sapaan seperti 'halo', 'hai', ucapan terima kasih, atau pertanyaan di luar topik database/dokumen)\n\n"
        "CONTOH:\n"
        "User: Berikan saya daftar driver\nAI: 1\n"
        "User: Apa status logistik saat ini?\nAI: 1\n"
        "User: Apa syarat pengiriman barang?\nAI: 2\n"
        "User: halo\nAI: 3\n\n"
        "Pertanyaan User: {pertanyaan}\n"
        "AI:"
    )
    
    keputusan = llm.invoke(router_prompt.format(pertanyaan=pertanyaan)).strip()
    print(f"\n[AI ROUTER] Memilih Kategori: {keputusan}")

    # LANGKAH B: Eksekusi Berdasarkan Keputusan
    if "1" in keputusan:
        # --- JALUR ANGKA / SQL (TEXT-TO-SQL) ---
        try:
            skema_db = sql_db.get_table_info()
            
            # 2. Minta AI menyusun query SQL (DENGAN CONTOH AGAR AI TIDAK BINGUNG)
            sql_prompt = PromptTemplate.from_template(
                "Anda adalah ahli analis data MySQL. Berdasarkan skema tabel berikut, buatlah query SQL yang benar untuk menjawab pertanyaan user.\n\n"
                "Skema Tabel:\n{skema}\n\n"
                "CONTOH:\n"
                "User: berikan saya daftar driver\n"
                "SQL: SELECT name, status FROM driver\n\n"
                "User: berapa jumlah toko?\n"
                "SQL: SELECT COUNT(*) FROM stores\n\n"
                "Pertanyaan: {pertanyaan}\n\n"
                "ATURAN: Berikan HANYA kode SQL-nya saja tanpa penjelasan, tanpa format markdown ```sql, dan tanpa titik koma di akhir."
            )
            sql_query_mentah = llm.invoke(sql_prompt.format(skema=skema_db, pertanyaan=pertanyaan))
            
            sql_query = re.sub(r"```sql|```", "", sql_query_mentah).strip()
            print(f"[SQL DIEKSEKUSI] {sql_query}")
            
            hasil_db = sql_db.run(sql_query)
            print(f"[HASIL DATABASE] {hasil_db}")
            
            jawaban_prompt = PromptTemplate.from_template(
                "Anda adalah asisten AI cerdas untuk PT SAGE. Jawablah pertanyaan user seluwes dan senatural mungkin layaknya asisten manusia yang pintar.\n\n"
                "Pertanyaan User: {pertanyaan}\n"
                "Informasi/Fakta: {hasil_db}\n\n"
                "ATURAN:\n"
                "1. Mengobrollah dengan gaya yang natural, ramah, dan langsung pada intinya (seperti gaya bahasa ChatGPT).\n"
                "2. Jangan gunakan template baku. Jika data butuh dirangkum, rangkum secara pintar. Jika pertanyaannya sederhana, jawablah dengan singkat tanpa perlu kesimpulan yang panjang lebar.\n"
                "3. JANGAN PERNAH menyebut kata 'SQL', 'Database', 'Query', 'Array', atau 'Data mentah' dalam jawaban Anda. Anggap saja informasi tersebut ada di kepala Anda.\n\n"
                "Jawaban Anda:"
            )
            jawaban_akhir = llm.invoke(jawaban_prompt.format(pertanyaan=pertanyaan, hasil_db=hasil_db))
            
            return {
                "sumber": "MySQL",
                "jawaban": jawaban_akhir
            }
            
        except Exception as e:
            print(f"[ERROR SQL] {str(e)}")
            return {
                "sumber": "MySQL",
                "jawaban": "Maaf, saya tidak dapat menghitung data tersebut saat ini karena terjadi kendala pada sistem database."
            }
            
    elif "2" in keputusan:
        # --- JALUR DOKUMEN / RAG ---
        try:
            retriever = get_retriever()
            dokumen_relevan = retriever.get_relevant_documents(pertanyaan)
            
            konteks = "\n\n".join([doc.page_content for doc in dokumen_relevan])
            
            rag_prompt = PromptTemplate.from_template(
                "Anda adalah asisten perusahaan benih yang ramah. Jawab pertanyaan berdasarkan konteks dokumen aturan berikut.\n\n"
                "Konteks Aturan:\n{konteks}\n\n"
                "Pertanyaan: {pertanyaan}\n\n"
                "Jawaban:"
            )
            jawaban_akhir = llm.invoke(rag_prompt.format(konteks=konteks, pertanyaan=pertanyaan))
            
            return {
                "sumber": "ChromaDB",
                "jawaban": jawaban_akhir
            }
        except ValueError as e:
            raise HTTPException(status_code=400, detail=str(e))
            
    elif "3" in keputusan: 
        # --- JALUR 3: SAPAAN / UMUM ---
        return {
            "sumber": "Umum", 
            "jawaban": "Halo! Saya adalah Asisten AI dari sistem ini. Anda bisa bertanya kepada saya mengenai data penjualan/retur (angka), atau tentang aturan dan SOP perusahaan. Ada yang bisa saya bantu?"
        } 
        
    else:
        return {"sumber": "Umum", "jawaban": "Maaf, saya tidak mengerti apakah Anda mencari data angka atau dokumen aturan."}