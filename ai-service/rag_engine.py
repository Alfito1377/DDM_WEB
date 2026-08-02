import os
from langchain_community.document_loaders import PyPDFLoader, Docx2txtLoader, UnstructuredExcelLoader
from langchain_community.document_loaders.csv_loader import CSVLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_community.vectorstores import Chroma
from langchain_community.embeddings import OllamaEmbeddings

# Nama folder tempat database vektor akan disimpan di server lokal Anda
CHROMA_PATH = "chroma_db"

embedding_model = OllamaEmbeddings(model="nomic-embed-text")

def process_new_document(file_path):
    # 1. Deteksi format file
    file_extension = os.path.splitext(file_path)[1].lower()
    
    print(f"Membaca dokumen: {file_path}")
    
    # 2. Gunakan loader yang sesuai
    if file_extension == '.pdf':
        loader = PyPDFLoader(file_path)
    elif file_extension in ['.docx', '.doc']:
        loader = Docx2txtLoader(file_path)
    elif file_extension in ['.csv']:
        loader = CSVLoader(file_path)
    elif file_extension in ['.xlsx', '.xls']:
        # Catatan: UnstructuredExcelLoader bisa digunakan jika library unstructured diinstal,
        # tapi untuk kesederhanaan kita bisa merespons menggunakan alat pembaca dataframe atau excel
        loader = UnstructuredExcelLoader(file_path, mode="elements") 
    else:
        raise ValueError(f"Format file {file_extension} belum didukung.")

    # 3. Ekstrak teks dari dokumen
    documents = loader.load()

    # 4. Pecah teks menjadi paragraf kecil (Chunking)
    text_splitter = RecursiveCharacterTextSplitter(
        chunk_size=1000, 
        chunk_overlap=200
    )
    chunks = text_splitter.split_documents(documents)

    # 5. Ubah teks jadi vektor dan simpan ke ChromaDB (Menggunakan model Nomic)
    embeddings = OllamaEmbeddings(model="nomic-embed-text")
    vector_db_path = "./chroma_db"
    
    Chroma.from_documents(
        documents=chunks, 
        embedding=embeddings, 
        persist_directory=vector_db_path
    )
    
    print(f"Selesai! {len(chunks)} potongan teks berhasil dimasukkan ke memori AI.")
    return True

def get_retriever():
    """
    Fungsi ini akan dipanggil oleh Chatbot nantinya.
    Tugasnya mencari 3 paragraf dari dokumen (ChromaDB) yang paling cocok dengan pertanyaan user.
    """
    if not os.path.exists(CHROMA_PATH):
        raise ValueError("Database vektor belum ada. Harap upload dokumen terlebih dahulu.")
        
    db = Chroma(persist_directory=CHROMA_PATH, embedding_function=embedding_model)
    
    # Parameter "k"
    return db.as_retriever(search_kwargs={"k": 3})