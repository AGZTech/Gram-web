from fastapi import FastAPI, APIRouter, HTTPException, UploadFile, File, Form, Query
from fastapi.staticfiles import StaticFiles
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
from pathlib import Path
from pydantic import BaseModel, Field
from typing import List, Optional
import uuid
from datetime import datetime, timezone
import shutil

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# Create uploads directory
UPLOAD_DIR = ROOT_DIR / "uploads"
UPLOAD_DIR.mkdir(exist_ok=True)
for subdir in ["notices", "news", "schemes", "works", "gallery", "members", "services"]:
    (UPLOAD_DIR / subdir).mkdir(exist_ok=True)

# MongoDB connection
mongo_url = os.environ['MONGO_URL']
client = AsyncIOMotorClient(mongo_url)
db = client[os.environ['DB_NAME']]

app = FastAPI(title="Digital Gram Panchayat API")
api_router = APIRouter(prefix="/api")

# ============== MODELS ==============

class AdminBase(BaseModel):
    name: str
    email: str
    phone: Optional[str] = None
    role: str = "editor"
    is_active: bool = True

class AdminCreate(AdminBase):
    password: str

class Admin(AdminBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class LoginRequest(BaseModel):
    email: str
    password: str

class NoticeBase(BaseModel):
    title: str
    description: Optional[str] = None
    notice_date: str
    expiry_date: Optional[str] = None
    is_important: bool = False
    show_in_ticker: bool = True
    is_published: bool = True

class Notice(NoticeBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    file_path: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class NewsBase(BaseModel):
    title: str
    excerpt: Optional[str] = None
    content: str
    published_date: str
    is_featured: bool = False
    is_published: bool = True

class News(NewsBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    slug: str = ""
    featured_image: Optional[str] = None
    views: int = 0
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class SchemeBase(BaseModel):
    title: str
    description: str
    eligibility: Optional[str] = None
    benefits: Optional[str] = None
    documents_required: Optional[str] = None
    how_to_apply: Optional[str] = None
    gr_link: Optional[str] = None
    department: Optional[str] = None
    is_active: bool = True
    is_published: bool = True

class Scheme(SchemeBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    slug: str = ""
    pdf_file: Optional[str] = None
    featured_image: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class WorkBase(BaseModel):
    title: str
    description: Optional[str] = None
    location: Optional[str] = None
    budget: Optional[float] = None
    spent_amount: Optional[float] = None
    start_date: Optional[str] = None
    completion_date: Optional[str] = None
    status: str = "planned"
    progress_percentage: int = 0
    contractor_name: Optional[str] = None
    is_published: bool = True

class Work(WorkBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    featured_image: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ServiceBase(BaseModel):
    title: str
    description: str
    process: Optional[str] = None
    documents_required: Optional[str] = None
    fees: Optional[str] = None
    time_duration: Optional[str] = None
    icon: Optional[str] = None
    sort_order: int = 0
    is_published: bool = True

class Service(ServiceBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    slug: str = ""
    pdf_file: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class MemberBase(BaseModel):
    name: str
    designation: str
    phone: Optional[str] = None
    email: Optional[str] = None
    bio: Optional[str] = None
    ward_number: Optional[str] = None
    term_start: Optional[str] = None
    term_end: Optional[str] = None
    is_active: bool = True
    sort_order: int = 0

class Member(MemberBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    photo: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class GalleryAlbumBase(BaseModel):
    title: str
    description: Optional[str] = None
    event_date: Optional[str] = None
    is_published: bool = True
    sort_order: int = 0

class GalleryAlbum(GalleryAlbumBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    slug: str = ""
    cover_image: Optional[str] = None
    photos: List[str] = []
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ContactInquiryBase(BaseModel):
    name: str
    email: Optional[str] = None
    phone: str
    subject: str
    message: str

class ContactInquiry(ContactInquiryBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    status: str = "new"
    admin_notes: Optional[str] = None
    ip_address: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class PageBase(BaseModel):
    title: str
    slug: str
    content: Optional[str] = None
    meta_title: Optional[str] = None
    meta_description: Optional[str] = None
    is_published: bool = True

class Page(PageBase):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    featured_image: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class Setting(BaseModel):
    key: str
    value: str
    type: str = "text"
    group: str = "general"

class VisitorCount(BaseModel):
    total: int = 0
    today: int = 0
    monthly: int = 0

# ============== HELPER FUNCTIONS ==============

def slugify(text: str) -> str:
    import re
    text = text.lower().strip()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[-\s]+', '-', text)
    return text + '-' + str(int(datetime.now().timestamp()))

def serialize_doc(doc):
    if doc and '_id' in doc:
        del doc['_id']
    if doc and 'created_at' in doc and isinstance(doc['created_at'], datetime):
        doc['created_at'] = doc['created_at'].isoformat()
    return doc

# ============== AUTH ROUTES ==============

@api_router.post("/auth/login")
async def login(request: LoginRequest):
    admin = await db.admins.find_one({"email": request.email}, {"_id": 0})
    if not admin or admin.get("password") != request.password:
        raise HTTPException(status_code=401, detail="चुकीचे ईमेल किंवा पासवर्ड")
    if not admin.get("is_active"):
        raise HTTPException(status_code=401, detail="खाते निष्क्रिय आहे")
    await db.admins.update_one({"email": request.email}, {"$set": {"last_login": datetime.now(timezone.utc).isoformat()}})
    return {"message": "लॉगिन यशस्वी", "admin": {k: v for k, v in admin.items() if k != "password"}}

# ============== NOTICE ROUTES ==============

@api_router.get("/notices", response_model=List[dict])
async def get_notices(published_only: bool = False, ticker_only: bool = False):
    query = {}
    if published_only:
        query["is_published"] = True
    if ticker_only:
        query["show_in_ticker"] = True
    notices = await db.notices.find(query, {"_id": 0}).sort("notice_date", -1).to_list(100)
    return [serialize_doc(n) for n in notices]

@api_router.get("/notices/{notice_id}")
async def get_notice(notice_id: str):
    notice = await db.notices.find_one({"id": notice_id}, {"_id": 0})
    if not notice:
        raise HTTPException(status_code=404, detail="नोटीस सापडली नाही")
    return serialize_doc(notice)

@api_router.post("/notices")
async def create_notice(notice: NoticeBase):
    notice_dict = notice.model_dump()
    notice_obj = Notice(**notice_dict)
    doc = notice_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.notices.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/notices/{notice_id}")
async def update_notice(notice_id: str, notice: NoticeBase):
    result = await db.notices.update_one({"id": notice_id}, {"$set": notice.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="नोटीस सापडली नाही")
    return {"message": "नोटीस अपडेट झाली"}

@api_router.delete("/notices/{notice_id}")
async def delete_notice(notice_id: str):
    result = await db.notices.delete_one({"id": notice_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="नोटीस सापडली नाही")
    return {"message": "नोटीस हटवली गेली"}

# ============== NEWS ROUTES ==============

@api_router.get("/news", response_model=List[dict])
async def get_news(published_only: bool = False, featured_only: bool = False, limit: int = 100):
    query = {}
    if published_only:
        query["is_published"] = True
    if featured_only:
        query["is_featured"] = True
    news = await db.news.find(query, {"_id": 0}).sort("published_date", -1).to_list(limit)
    return [serialize_doc(n) for n in news]

@api_router.get("/news/{news_id}")
async def get_news_item(news_id: str):
    news = await db.news.find_one({"id": news_id}, {"_id": 0})
    if not news:
        raise HTTPException(status_code=404, detail="बातमी सापडली नाही")
    await db.news.update_one({"id": news_id}, {"$inc": {"views": 1}})
    return serialize_doc(news)

@api_router.post("/news")
async def create_news(news: NewsBase):
    news_dict = news.model_dump()
    news_obj = News(**news_dict)
    news_obj.slug = slugify(news.title)
    doc = news_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.news.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/news/{news_id}")
async def update_news(news_id: str, news: NewsBase):
    result = await db.news.update_one({"id": news_id}, {"$set": news.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="बातमी सापडली नाही")
    return {"message": "बातमी अपडेट झाली"}

@api_router.delete("/news/{news_id}")
async def delete_news(news_id: str):
    result = await db.news.delete_one({"id": news_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="बातमी सापडली नाही")
    return {"message": "बातमी हटवली गेली"}

# ============== SCHEME ROUTES ==============

@api_router.get("/schemes", response_model=List[dict])
async def get_schemes(published_only: bool = False, limit: int = 100):
    query = {}
    if published_only:
        query["is_published"] = True
        query["is_active"] = True
    schemes = await db.schemes.find(query, {"_id": 0}).sort("created_at", -1).to_list(limit)
    return [serialize_doc(s) for s in schemes]

@api_router.get("/schemes/{scheme_id}")
async def get_scheme(scheme_id: str):
    scheme = await db.schemes.find_one({"id": scheme_id}, {"_id": 0})
    if not scheme:
        raise HTTPException(status_code=404, detail="योजना सापडली नाही")
    return serialize_doc(scheme)

@api_router.post("/schemes")
async def create_scheme(scheme: SchemeBase):
    scheme_dict = scheme.model_dump()
    scheme_obj = Scheme(**scheme_dict)
    scheme_obj.slug = slugify(scheme.title)
    doc = scheme_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.schemes.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/schemes/{scheme_id}")
async def update_scheme(scheme_id: str, scheme: SchemeBase):
    result = await db.schemes.update_one({"id": scheme_id}, {"$set": scheme.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="योजना सापडली नाही")
    return {"message": "योजना अपडेट झाली"}

@api_router.delete("/schemes/{scheme_id}")
async def delete_scheme(scheme_id: str):
    result = await db.schemes.delete_one({"id": scheme_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="योजना सापडली नाही")
    return {"message": "योजना हटवली गेली"}

# ============== DEVELOPMENT WORKS ROUTES ==============

@api_router.get("/works", response_model=List[dict])
async def get_works(published_only: bool = False, status: Optional[str] = None):
    query = {}
    if published_only:
        query["is_published"] = True
    if status:
        query["status"] = status
    works = await db.works.find(query, {"_id": 0}).sort("created_at", -1).to_list(100)
    return [serialize_doc(w) for w in works]

@api_router.get("/works/{work_id}")
async def get_work(work_id: str):
    work = await db.works.find_one({"id": work_id}, {"_id": 0})
    if not work:
        raise HTTPException(status_code=404, detail="विकासकाम सापडले नाही")
    return serialize_doc(work)

@api_router.post("/works")
async def create_work(work: WorkBase):
    work_dict = work.model_dump()
    work_obj = Work(**work_dict)
    doc = work_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.works.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/works/{work_id}")
async def update_work(work_id: str, work: WorkBase):
    result = await db.works.update_one({"id": work_id}, {"$set": work.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="विकासकाम सापडले नाही")
    return {"message": "विकासकाम अपडेट झाले"}

@api_router.delete("/works/{work_id}")
async def delete_work(work_id: str):
    result = await db.works.delete_one({"id": work_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="विकासकाम सापडले नाही")
    return {"message": "विकासकाम हटवले गेले"}

# ============== SERVICES ROUTES ==============

@api_router.get("/services", response_model=List[dict])
async def get_services(published_only: bool = False):
    query = {}
    if published_only:
        query["is_published"] = True
    services = await db.services.find(query, {"_id": 0}).sort("sort_order", 1).to_list(100)
    return [serialize_doc(s) for s in services]

@api_router.get("/services/{service_id}")
async def get_service(service_id: str):
    service = await db.services.find_one({"id": service_id}, {"_id": 0})
    if not service:
        raise HTTPException(status_code=404, detail="सेवा सापडली नाही")
    return serialize_doc(service)

@api_router.post("/services")
async def create_service(service: ServiceBase):
    service_dict = service.model_dump()
    service_obj = Service(**service_dict)
    service_obj.slug = slugify(service.title)
    doc = service_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.services.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/services/{service_id}")
async def update_service(service_id: str, service: ServiceBase):
    result = await db.services.update_one({"id": service_id}, {"$set": service.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="सेवा सापडली नाही")
    return {"message": "सेवा अपडेट झाली"}

@api_router.delete("/services/{service_id}")
async def delete_service(service_id: str):
    result = await db.services.delete_one({"id": service_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="सेवा सापडली नाही")
    return {"message": "सेवा हटवली गेली"}

# ============== MEMBERS ROUTES ==============

@api_router.get("/members", response_model=List[dict])
async def get_members(active_only: bool = False):
    query = {}
    if active_only:
        query["is_active"] = True
    members = await db.members.find(query, {"_id": 0}).sort("sort_order", 1).to_list(100)
    return [serialize_doc(m) for m in members]

@api_router.get("/members/{member_id}")
async def get_member(member_id: str):
    member = await db.members.find_one({"id": member_id}, {"_id": 0})
    if not member:
        raise HTTPException(status_code=404, detail="सदस्य सापडला नाही")
    return serialize_doc(member)

@api_router.post("/members")
async def create_member(member: MemberBase):
    member_dict = member.model_dump()
    member_obj = Member(**member_dict)
    doc = member_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.members.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/members/{member_id}")
async def update_member(member_id: str, member: MemberBase):
    result = await db.members.update_one({"id": member_id}, {"$set": member.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="सदस्य सापडला नाही")
    return {"message": "सदस्य अपडेट झाला"}

@api_router.delete("/members/{member_id}")
async def delete_member(member_id: str):
    result = await db.members.delete_one({"id": member_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="सदस्य सापडला नाही")
    return {"message": "सदस्य हटवला गेला"}

# ============== GALLERY ROUTES ==============

@api_router.get("/gallery", response_model=List[dict])
async def get_albums(published_only: bool = False):
    query = {}
    if published_only:
        query["is_published"] = True
    albums = await db.gallery.find(query, {"_id": 0}).sort("event_date", -1).to_list(100)
    return [serialize_doc(a) for a in albums]

@api_router.get("/gallery/{album_id}")
async def get_album(album_id: str):
    album = await db.gallery.find_one({"id": album_id}, {"_id": 0})
    if not album:
        raise HTTPException(status_code=404, detail="अल्बम सापडला नाही")
    return serialize_doc(album)

@api_router.post("/gallery")
async def create_album(album: GalleryAlbumBase):
    album_dict = album.model_dump()
    album_obj = GalleryAlbum(**album_dict)
    album_obj.slug = slugify(album.title)
    doc = album_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.gallery.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/gallery/{album_id}")
async def update_album(album_id: str, album: GalleryAlbumBase):
    result = await db.gallery.update_one({"id": album_id}, {"$set": album.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="अल्बम सापडला नाही")
    return {"message": "अल्बम अपडेट झाला"}

@api_router.delete("/gallery/{album_id}")
async def delete_album(album_id: str):
    result = await db.gallery.delete_one({"id": album_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="अल्बम सापडला नाही")
    return {"message": "अल्बम हटवला गेला"}

# ============== CONTACT INQUIRY ROUTES ==============

@api_router.get("/inquiries", response_model=List[dict])
async def get_inquiries(status: Optional[str] = None):
    query = {}
    if status:
        query["status"] = status
    inquiries = await db.inquiries.find(query, {"_id": 0}).sort("created_at", -1).to_list(100)
    return [serialize_doc(i) for i in inquiries]

@api_router.post("/inquiries")
async def create_inquiry(inquiry: ContactInquiryBase):
    inquiry_dict = inquiry.model_dump()
    inquiry_obj = ContactInquiry(**inquiry_dict)
    doc = inquiry_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.inquiries.insert_one(doc)
    return {"message": "तुमचा संदेश यशस्वीरित्या पाठवला गेला"}

@api_router.put("/inquiries/{inquiry_id}/status")
async def update_inquiry_status(inquiry_id: str, status: str, admin_notes: Optional[str] = None):
    update_data = {"status": status}
    if admin_notes:
        update_data["admin_notes"] = admin_notes
    result = await db.inquiries.update_one({"id": inquiry_id}, {"$set": update_data})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="चौकशी सापडली नाही")
    return {"message": "स्थिती अपडेट झाली"}

@api_router.delete("/inquiries/{inquiry_id}")
async def delete_inquiry(inquiry_id: str):
    result = await db.inquiries.delete_one({"id": inquiry_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="चौकशी सापडली नाही")
    return {"message": "चौकशी हटवली गेली"}

# ============== PAGES ROUTES ==============

@api_router.get("/pages", response_model=List[dict])
async def get_pages(published_only: bool = False):
    query = {}
    if published_only:
        query["is_published"] = True
    pages = await db.pages.find(query, {"_id": 0}).to_list(100)
    return [serialize_doc(p) for p in pages]

@api_router.get("/pages/{slug}")
async def get_page(slug: str):
    page = await db.pages.find_one({"slug": slug}, {"_id": 0})
    if not page:
        raise HTTPException(status_code=404, detail="पेज सापडले नाही")
    return serialize_doc(page)

@api_router.post("/pages")
async def create_page(page: PageBase):
    page_dict = page.model_dump()
    page_obj = Page(**page_dict)
    doc = page_obj.model_dump()
    doc['created_at'] = doc['created_at'].isoformat()
    await db.pages.insert_one(doc)
    return serialize_doc(doc)

@api_router.put("/pages/{page_id}")
async def update_page(page_id: str, page: PageBase):
    result = await db.pages.update_one({"id": page_id}, {"$set": page.model_dump()})
    if result.modified_count == 0:
        raise HTTPException(status_code=404, detail="पेज सापडले नाही")
    return {"message": "पेज अपडेट झाले"}

# ============== SETTINGS ROUTES ==============

@api_router.get("/settings")
async def get_settings():
    settings = await db.settings.find({}, {"_id": 0}).to_list(100)
    return {s["key"]: s["value"] for s in settings}

@api_router.post("/settings")
async def update_settings(settings: List[Setting]):
    for setting in settings:
        await db.settings.update_one(
            {"key": setting.key},
            {"$set": setting.model_dump()},
            upsert=True
        )
    return {"message": "सेटिंग्ज अपडेट झाल्या"}

# ============== VISITOR COUNTER ==============

@api_router.get("/visitors/count")
async def get_visitor_count():
    stats = await db.visitor_stats.find_one({"id": "main"}, {"_id": 0})
    if not stats:
        stats = {"total": 0, "today": 0, "monthly": 0}
    return stats

@api_router.post("/visitors/track")
async def track_visitor():
    today = datetime.now(timezone.utc).strftime("%Y-%m-%d")
    month = datetime.now(timezone.utc).strftime("%Y-%m")
    
    # Update total count
    await db.visitor_stats.update_one(
        {"id": "main"},
        {"$inc": {"total": 1}, "$set": {"last_updated": datetime.now(timezone.utc).isoformat()}},
        upsert=True
    )
    
    # Update daily count
    await db.visitor_daily.update_one(
        {"date": today},
        {"$inc": {"count": 1}},
        upsert=True
    )
    
    return {"message": "tracked"}

@api_router.get("/admin/stats")
async def get_admin_stats():
    notices_count = await db.notices.count_documents({})
    news_count = await db.news.count_documents({})
    schemes_count = await db.schemes.count_documents({})
    works_count = await db.works.count_documents({})
    inquiries_new = await db.inquiries.count_documents({"status": "new"})
    visitor_stats = await db.visitor_stats.find_one({"id": "main"}, {"_id": 0}) or {"total": 0}
    
    return {
        "notices": notices_count,
        "news": news_count,
        "schemes": schemes_count,
        "works": works_count,
        "inquiries_new": inquiries_new,
        "total_visitors": visitor_stats.get("total", 0)
    }

# ============== SEED DATA ==============

@api_router.post("/seed")
async def seed_database():
    # Check if already seeded
    existing = await db.admins.find_one({"email": "admin@grampanchayat.gov.in"})
    if existing:
        return {"message": "Database already seeded"}
    
    # Seed Admin
    admin = {
        "id": str(uuid.uuid4()),
        "name": "प्रशासक",
        "email": "admin@grampanchayat.gov.in",
        "password": "Admin@123",
        "phone": "9876543210",
        "role": "super_admin",
        "is_active": True,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.admins.insert_one(admin)
    
    # Seed Settings
    settings = [
        {"key": "site_name", "value": "ग्रामपंचायत आदर्शगाव", "type": "text", "group": "general"},
        {"key": "site_tagline", "value": "स्वच्छ गाव, समृद्ध गाव", "type": "text", "group": "general"},
        {"key": "contact_email", "value": "grampanchayat@gov.in", "type": "email", "group": "contact"},
        {"key": "contact_phone", "value": "02XX-XXXXXX", "type": "text", "group": "contact"},
        {"key": "contact_address", "value": "ग्रामपंचायत कार्यालय, आदर्शगाव, तालुका - xyz, जिल्हा - xyz, महाराष्ट्र", "type": "textarea", "group": "contact"},
        {"key": "sarpanch_message", "value": "आपल्या गावाच्या सर्वांगीण विकासासाठी आम्ही कटिबद्ध आहोत. ग्रामपंचायतीच्या या डिजिटल व्यासपीठाद्वारे आपण सर्व शासकीय योजना, विकासकामे आणि इतर महत्त्वाची माहिती सहजपणे मिळवू शकता.", "type": "textarea", "group": "content"},
    ]
    await db.settings.insert_many(settings)
    
    # Seed Members
    members = [
        {"id": str(uuid.uuid4()), "name": "श्री. राजेंद्र पाटील", "designation": "sarpanch", "phone": "9876543201", "bio": "गेल्या 10 वर्षांपासून सामाजिक कार्यात सक्रिय", "ward_number": "-", "is_active": True, "sort_order": 1, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "name": "श्रीमती. सुनीता शिंदे", "designation": "up_sarpanch", "phone": "9876543202", "bio": "महिला सक्षमीकरण क्षेत्रात कार्यरत", "ward_number": "-", "is_active": True, "sort_order": 2, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "name": "श्री. अमित जाधव", "designation": "gram_sevak", "phone": "9876543203", "bio": "ग्रामसेवक", "is_active": True, "sort_order": 3, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.members.insert_many(members)
    
    # Seed Notices
    notices = [
        {"id": str(uuid.uuid4()), "title": "ग्रामसभा बैठक सूचना - जानेवारी 2025", "description": "सर्व ग्रामस्थांना कळविण्यात येते की, दिनांक 26 जानेवारी 2025 रोजी सकाळी 10 वाजता ग्रामपंचायत कार्यालयात ग्रामसभा बैठक आयोजित करण्यात आली आहे.", "notice_date": "2025-01-20", "is_important": True, "show_in_ticker": True, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "पाणीपुरवठा वेळापत्रक बदल", "description": "उन्हाळी हंगामामुळे पाणीपुरवठा वेळापत्रकात बदल करण्यात आला आहे.", "notice_date": "2025-01-15", "is_important": False, "show_in_ticker": True, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "मालमत्ता कर भरणा अंतिम तारीख", "description": "मालमत्ता कर भरणा करण्याची अंतिम तारीख 31 मार्च 2025 आहे.", "notice_date": "2025-01-10", "is_important": True, "show_in_ticker": True, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.notices.insert_many(notices)
    
    # Seed News
    news = [
        {"id": str(uuid.uuid4()), "title": "गावाला राज्यस्तरीय स्वच्छता पुरस्कार", "slug": "swachhata-puraskar", "excerpt": "आदर्शगाव ग्रामपंचायतीला स्वच्छ भारत अभियान अंतर्गत राज्यस्तरीय पुरस्कार जाहीर.", "content": "आदर्शगाव ग्रामपंचायतीच्या स्वच्छता अभियानाला यश मिळाले असून राज्य शासनाने या गावाला राज्यस्तरीय स्वच्छता पुरस्काराने गौरविले आहे.", "published_date": "2025-01-18", "is_featured": True, "is_published": True, "views": 156, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "नवीन पाणीपुरवठा योजनेचे उद्घाटन", "slug": "pani-puravtha", "excerpt": "जलजीवन मिशन अंतर्गत गावात नवीन पाणीपुरवठा योजनेचे उद्घाटन.", "content": "जलजीवन मिशन अंतर्गत आदर्शगाव मध्ये रु. 50 लाखांच्या पाणीपुरवठा योजनेचे उद्घाटन जिल्हाधिकारी यांच्या हस्ते करण्यात आले.", "published_date": "2025-01-12", "is_featured": False, "is_published": True, "views": 89, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.news.insert_many(news)
    
    # Seed Schemes
    schemes = [
        {"id": str(uuid.uuid4()), "title": "प्रधानमंत्री आवास योजना (ग्रामीण)", "slug": "pmay-gramin", "description": "ग्रामीण भागातील गरीब कुटुंबांना पक्के घर बांधण्यासाठी आर्थिक सहाय्य.", "eligibility": "कुटुंबाचे उत्पन्न 3 लाखांपेक्षा कमी असावे", "benefits": "मैदानी भागासाठी रु. 1,20,000", "documents_required": "आधार कार्ड, रेशन कार्ड, उत्पन्नाचा दाखला", "department": "ग्रामविकास विभाग", "gr_link": "https://pmayg.nic.in/", "is_active": True, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "लाडकी बहीण योजना", "slug": "ladki-bahin", "description": "महाराष्ट्र शासनाची महिला सक्षमीकरण योजना.", "eligibility": "महाराष्ट्राची रहिवासी महिला, वय 21-65 वर्षे", "benefits": "दरमहा रु. 1,500 आर्थिक सहाय्य", "documents_required": "आधार कार्ड, रेशन कार्ड, बँक पासबुक", "department": "महिला व बालविकास विभाग", "is_active": True, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.schemes.insert_many(schemes)
    
    # Seed Development Works
    works = [
        {"id": str(uuid.uuid4()), "title": "ग्रामीण रस्ता डांबरीकरण", "description": "मुख्य गावठाण ते शिवार रस्त्याचे डांबरीकरण", "location": "मुख्य गावठाण ते शिवार", "budget": 2500000, "spent_amount": 2200000, "status": "completed", "progress_percentage": 100, "contractor_name": "श्री. विकास बांधकाम", "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "समाज मंदिर बांधकाम", "description": "गावात नवीन समाज मंदिराचे बांधकाम", "location": "ग्रामपंचायत कार्यालय जवळ", "budget": 1500000, "spent_amount": 900000, "status": "in_progress", "progress_percentage": 60, "contractor_name": "आदर्श कन्स्ट्रक्शन", "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "LED पथदिवे बसविणे", "description": "गावातील मुख्य रस्त्यांवर 50 LED पथदिवे", "location": "संपूर्ण गाव", "budget": 500000, "status": "planned", "progress_percentage": 0, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.works.insert_many(works)
    
    # Seed Services
    services = [
        {"id": str(uuid.uuid4()), "title": "जन्म दाखला", "slug": "janma-dakhla", "description": "जन्माची नोंदणी आणि जन्म दाखला मिळविण्यासाठी", "process": "ग्रामपंचायत कार्यालयात अर्ज करा", "documents_required": "रुग्णालयाचा जन्म रिपोर्ट, आधार कार्ड", "fees": "रु. 10", "time_duration": "7 दिवस", "icon": "Baby", "sort_order": 1, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "मृत्यू दाखला", "slug": "mrityu-dakhla", "description": "मृत्यूची नोंदणी आणि मृत्यू दाखला", "process": "ग्रामपंचायत कार्यालयात अर्ज करा", "documents_required": "मृत्यू रिपोर्ट, आधार कार्ड", "fees": "रु. 10", "time_duration": "7 दिवस", "icon": "FileText", "sort_order": 2, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "रहिवासी दाखला", "slug": "rahivasi-dakhla", "description": "रहिवासी असल्याचा दाखला", "process": "ग्रामपंचायत कार्यालयात अर्ज करा", "documents_required": "आधार कार्ड, रेशन कार्ड, वीज बिल", "fees": "रु. 20", "time_duration": "3 दिवस", "icon": "Home", "sort_order": 3, "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.services.insert_many(services)
    
    # Seed Pages
    pages = [
        {"id": str(uuid.uuid4()), "title": "आमच्याबद्दल", "slug": "about", "content": "<h2>ग्रामपंचायत आदर्शगाव</h2><p>आदर्शगाव हे महाराष्ट्रातील एक प्रगतीशील गाव आहे. आमच्या ग्रामपंचायतीची स्थापना 1960 साली झाली.</p>", "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
        {"id": str(uuid.uuid4()), "title": "इतिहास", "slug": "history", "content": "<h2>गावाचा इतिहास</h2><p>आदर्शगाव हे प्राचीन इतिहास असलेले गाव आहे.</p>", "is_published": True, "created_at": datetime.now(timezone.utc).isoformat()},
    ]
    await db.pages.insert_many(pages)
    
    # Initialize visitor count
    await db.visitor_stats.update_one(
        {"id": "main"},
        {"$set": {"total": 1, "last_updated": datetime.now(timezone.utc).isoformat()}},
        upsert=True
    )
    
    return {"message": "Database seeded successfully with Marathi demo data"}

# Include the router
app.include_router(api_router)

# Serve uploads
app.mount("/uploads", StaticFiles(directory=str(UPLOAD_DIR)), name="uploads")

app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

@app.on_event("shutdown")
async def shutdown_db_client():
    client.close()
