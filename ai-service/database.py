from sqlalchemy import create_engine, Column, Integer, String, Float, Date
from sqlalchemy.orm import declarative_base
from sqlalchemy.orm import sessionmaker

# 1. Konfigurasi Koneksi MySQL
# Ganti 'root' dan '' sesuai dengan username dan password MySQL lokal Anda (misal di XAMPP).
# Format: mysql+pymysql://username:password@host:port/nama_database
DATABASE_URL = "mysql+pymysql://root:@localhost:3306/SAGE"

# Membuat mesin koneksi
engine = create_engine(DATABASE_URL)

SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Base class 
Base = declarative_base()

# 2. Definisi Skema Tabel 
class SeedTransaction(Base):
    __tablename__ = "seed_transactions"

    id = Column(Integer, primary_key=True, index=True)
    tanggal = Column(Date, nullable=False)
    jenis_transaksi = Column(String(50), nullable=False) 
    varietas_benih = Column(String(100), nullable=False)
    jumlah_kg = Column(Float, nullable=False)           
    alasan_retur = Column(String(255), nullable=True)   

# 3. Fungsi database
def init_db():
    Base.metadata.create_all(bind=engine)
    print("Database dan tabel berhasil diinisialisasi!")

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()