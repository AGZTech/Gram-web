import { useEffect, useState } from "react";
import { BrowserRouter, Routes, Route, Link, useLocation, useNavigate } from "react-router-dom";
import axios from "axios";
import { Toaster, toast } from "sonner";
import { 
  Landmark, Users, FileText, Phone, Menu, X, ChevronDown, 
  Bell, Newspaper, HardHat, HandCoins, Images, Home, Clock,
  MapPin, Mail, ExternalLink, ArrowRight, Eye, Calendar,
  Building, Baby, FileCheck, Droplets, IndianRupee, Shield,
  LayoutDashboard, Settings, LogOut, Plus, Edit, Trash2, Search
} from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

// ============== CONTEXT ==============
import { createContext, useContext } from "react";

const AuthContext = createContext(null);

const AuthProvider = ({ children }) => {
  const [admin, setAdmin] = useState(() => {
    const saved = localStorage.getItem("admin");
    return saved ? JSON.parse(saved) : null;
  });

  const login = (adminData) => {
    setAdmin(adminData);
    localStorage.setItem("admin", JSON.stringify(adminData));
  };

  const logout = () => {
    setAdmin(null);
    localStorage.removeItem("admin");
  };

  return (
    <AuthContext.Provider value={{ admin, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

const useAuth = () => useContext(AuthContext);

// ============== COMPONENTS ==============

const Header = ({ settings }) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const location = useLocation();

  const navLinks = [
    { path: "/", label: "मुखपृष्ठ", icon: Home },
    { path: "/services", label: "लोकसेवा", icon: Users },
    { path: "/schemes", label: "योजना", icon: HandCoins },
    { path: "/works", label: "विकासकामे", icon: HardHat },
    { path: "/notices", label: "नोटीस", icon: Bell },
    { path: "/news", label: "बातम्या", icon: Newspaper },
    { path: "/gallery", label: "गॅलरी", icon: Images },
    { path: "/contact", label: "संपर्क", icon: Phone },
  ];

  return (
    <>
      {/* Top Bar */}
      <div className="bg-slate-900 text-white py-2 text-sm">
        <div className="max-w-7xl mx-auto px-4 flex flex-wrap justify-between items-center">
          <div className="flex items-center gap-4">
            <a href={`mailto:${settings?.contact_email || 'grampanchayat@gov.in'}`} className="hover:text-orange-400 transition flex items-center gap-1">
              <Mail className="w-4 h-4" />
              <span className="hidden sm:inline">{settings?.contact_email || 'grampanchayat@gov.in'}</span>
            </a>
            <a href={`tel:${settings?.contact_phone}`} className="hover:text-orange-400 transition hidden sm:flex items-center gap-1">
              <Phone className="w-4 h-4" />
              {settings?.contact_phone || '02XX-XXXXXX'}
            </a>
          </div>
          <Link to="/admin" className="hover:text-orange-400 transition flex items-center gap-1">
            <Shield className="w-4 h-4" />
            प्रशासक
          </Link>
        </div>
      </div>

      {/* Header */}
      <header className="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-200">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex items-center justify-between py-4">
            {/* Logo */}
            <Link to="/" className="flex items-center gap-3">
              <div className="w-14 h-14 bg-orange-500 rounded-lg flex items-center justify-center shadow-md">
                <Landmark className="w-8 h-8 text-white" />
              </div>
              <div>
                <h1 className="text-xl md:text-2xl font-bold text-slate-900 leading-tight">
                  {settings?.site_name || 'ग्रामपंचायत आदर्शगाव'}
                </h1>
                <p className="text-xs text-slate-500">{settings?.site_tagline || 'स्वच्छ गाव, समृद्ध गाव'}</p>
              </div>
            </Link>

            {/* Desktop Nav */}
            <nav className="hidden lg:flex items-center gap-1">
              {navLinks.slice(0, 4).map((link) => (
                <Link
                  key={link.path}
                  to={link.path}
                  className={`px-4 py-2 rounded-md font-medium transition-colors ${
                    location.pathname === link.path
                      ? 'text-orange-600 bg-orange-50'
                      : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50'
                  }`}
                >
                  {link.label}
                </Link>
              ))}
              
              {/* Dropdown */}
              <div className="relative" onMouseEnter={() => setDropdownOpen(true)} onMouseLeave={() => setDropdownOpen(false)}>
                <button className="px-4 py-2 rounded-md font-medium text-slate-700 hover:text-orange-600 hover:bg-slate-50 transition-colors flex items-center gap-1">
                  अधिक <ChevronDown className="w-4 h-4" />
                </button>
                {dropdownOpen && (
                  <div className="absolute top-full left-0 bg-white shadow-lg rounded-lg py-2 w-48 border border-slate-100">
                    {navLinks.slice(4).map((link) => (
                      <Link
                        key={link.path}
                        to={link.path}
                        className="block px-4 py-2 hover:bg-slate-50 hover:text-orange-600 transition-colors"
                      >
                        {link.label}
                      </Link>
                    ))}
                    <Link to="/about" className="block px-4 py-2 hover:bg-slate-50 hover:text-orange-600">आमच्याबद्दल</Link>
                    <Link to="/members" className="block px-4 py-2 hover:bg-slate-50 hover:text-orange-600">सदस्य</Link>
                  </div>
                )}
              </div>

              <Link to="/contact" className="ml-2 bg-orange-500 text-white px-5 py-2 rounded-md font-semibold hover:bg-orange-600 transition-colors shadow-sm">
                संपर्क करा
              </Link>
            </nav>

            {/* Mobile Menu Button */}
            <button onClick={() => setMobileMenuOpen(!mobileMenuOpen)} className="lg:hidden text-slate-700 p-2" data-testid="mobile-menu-btn">
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>

        {/* Mobile Nav */}
        {mobileMenuOpen && (
          <nav className="lg:hidden bg-white border-t border-slate-100 py-4">
            <div className="max-w-7xl mx-auto px-4 space-y-2">
              {navLinks.map((link) => (
                <Link
                  key={link.path}
                  to={link.path}
                  onClick={() => setMobileMenuOpen(false)}
                  className={`block py-2 px-4 rounded-md font-medium transition-colors ${
                    location.pathname === link.path
                      ? 'text-orange-600 bg-orange-50'
                      : 'text-slate-700 hover:text-orange-600 hover:bg-slate-50'
                  }`}
                >
                  {link.label}
                </Link>
              ))}
              <Link to="/about" onClick={() => setMobileMenuOpen(false)} className="block py-2 px-4 text-slate-700 hover:text-orange-600">आमच्याबद्दल</Link>
              <Link to="/members" onClick={() => setMobileMenuOpen(false)} className="block py-2 px-4 text-slate-700 hover:text-orange-600">सदस्य</Link>
            </div>
          </nav>
        )}
      </header>
    </>
  );
};

const NoticeTicker = ({ notices }) => {
  if (!notices || notices.length === 0) return null;

  return (
    <div className="bg-gradient-to-r from-orange-500 to-orange-600 py-2 overflow-hidden">
      <div className="ticker-animate inline-flex whitespace-nowrap">
        {[...notices, ...notices].map((notice, idx) => (
          <span key={idx} className="text-white px-8 flex items-center gap-2">
            <Bell className="w-4 h-4" />
            <strong>{notice.notice_date}:</strong> {notice.title}
            {notice.is_important && (
              <span className="bg-white text-orange-600 px-2 py-0.5 rounded text-xs font-bold ml-2">महत्त्वाचे</span>
            )}
          </span>
        ))}
      </div>
    </div>
  );
};

const Footer = ({ settings, visitorCount }) => {
  return (
    <footer className="bg-slate-900 text-white mt-16">
      <div className="max-w-7xl mx-auto px-4 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* About */}
          <div>
            <div className="flex items-center gap-3 mb-4">
              <div className="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                <Landmark className="w-6 h-6 text-white" />
              </div>
              <h3 className="text-xl font-bold">{settings?.site_name || 'ग्रामपंचायत आदर्शगाव'}</h3>
            </div>
            <p className="text-slate-400 text-sm">{settings?.site_tagline || 'स्वच्छ गाव, समृद्ध गाव'}</p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-lg font-semibold mb-4 text-orange-400">द्रुत दुवे</h4>
            <ul className="space-y-2 text-slate-400 text-sm">
              <li><Link to="/about" className="hover:text-orange-400 transition flex items-center gap-2"><ArrowRight className="w-3 h-3" />आमच्याबद्दल</Link></li>
              <li><Link to="/services" className="hover:text-orange-400 transition flex items-center gap-2"><ArrowRight className="w-3 h-3" />लोकसेवा</Link></li>
              <li><Link to="/schemes" className="hover:text-orange-400 transition flex items-center gap-2"><ArrowRight className="w-3 h-3" />योजना</Link></li>
              <li><Link to="/works" className="hover:text-orange-400 transition flex items-center gap-2"><ArrowRight className="w-3 h-3" />विकासकामे</Link></li>
              <li><Link to="/contact" className="hover:text-orange-400 transition flex items-center gap-2"><ArrowRight className="w-3 h-3" />संपर्क</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-lg font-semibold mb-4 text-orange-400">संपर्क</h4>
            <ul className="space-y-3 text-slate-400 text-sm">
              <li className="flex items-start gap-3">
                <MapPin className="w-4 h-4 mt-1 text-orange-400 flex-shrink-0" />
                <span>{settings?.contact_address || 'ग्रामपंचायत कार्यालय'}</span>
              </li>
              <li className="flex items-center gap-3">
                <Phone className="w-4 h-4 text-orange-400" />
                {settings?.contact_phone || '02XX-XXXXXX'}
              </li>
              <li className="flex items-center gap-3">
                <Mail className="w-4 h-4 text-orange-400" />
                {settings?.contact_email || 'grampanchayat@gov.in'}
              </li>
            </ul>
          </div>

          {/* Visitor Counter */}
          <div>
            <h4 className="text-lg font-semibold mb-4 text-orange-400">भेटीगार संख्या</h4>
            <div className="bg-slate-800 rounded-lg p-4">
              <div className="flex items-center gap-3 mb-3">
                <Users className="w-8 h-8 text-orange-400" />
                <div>
                  <p className="text-2xl font-bold">{visitorCount?.total?.toLocaleString() || '0'}</p>
                  <p className="text-xs text-slate-400">एकूण भेटीगार</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Copyright */}
      <div className="border-t border-slate-700 py-4">
        <div className="max-w-7xl mx-auto px-4 text-center text-slate-400 text-sm">
          © {new Date().getFullYear()} {settings?.site_name || 'ग्रामपंचायत आदर्शगाव'}. सर्व हक्क राखीव.
        </div>
      </div>
    </footer>
  );
};

// ============== PAGES ==============

const HomePage = ({ notices, news, schemes, works, members, settings }) => {
  const sarpanch = members?.find(m => m.designation === 'sarpanch');

  const quickLinks = [
    { icon: Users, label: 'लोकसेवा', path: '/services', color: 'from-blue-500 to-blue-600' },
    { icon: HandCoins, label: 'योजना', path: '/schemes', color: 'from-green-500 to-green-600' },
    { icon: HardHat, label: 'विकासकामे', path: '/works', color: 'from-yellow-500 to-yellow-600' },
    { icon: Bell, label: 'नोटीस', path: '/notices', color: 'from-red-500 to-red-600' },
    { icon: Images, label: 'गॅलरी', path: '/gallery', color: 'from-purple-500 to-purple-600' },
    { icon: Phone, label: 'संपर्क', path: '/contact', color: 'from-orange-500 to-orange-600' },
  ];

  const statusLabels = {
    planned: 'नियोजित',
    in_progress: 'प्रगतीपथावर',
    completed: 'पूर्ण',
    on_hold: 'स्थगित'
  };

  const statusColors = {
    planned: 'bg-blue-100 text-blue-800',
    in_progress: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    on_hold: 'bg-red-100 text-red-800'
  };

  return (
    <div data-testid="home-page">
      {/* Hero Section */}
      <section className="bg-slate-900 text-white relative overflow-hidden">
        <div className="absolute inset-0 opacity-20">
          <img 
            src="https://images.unsplash.com/photo-1624903715293-afe920c6adad?w=1920&q=80" 
            alt="Village" 
            className="w-full h-full object-cover"
          />
        </div>
        <div className="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-slate-900/70"></div>
        <div className="max-w-7xl mx-auto px-4 py-20 md:py-28 relative">
          <div className="max-w-2xl">
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
              स्वागत आहे<br />
              <span className="text-orange-400">{settings?.site_name || 'ग्रामपंचायत आदर्शगाव'}</span>
            </h1>
            <p className="text-lg md:text-xl text-slate-300 mb-8">
              {settings?.site_tagline || 'स्वच्छ गाव, समृद्ध गाव'} - डिजिटल इंडिया अभियानांतर्गत आमची अधिकृत वेबसाईट
            </p>
            <div className="flex flex-wrap gap-4">
              <Link to="/services" className="bg-orange-500 text-white px-6 py-3 rounded-md font-semibold hover:bg-orange-600 transition flex items-center gap-2 shadow-lg" data-testid="hero-services-btn">
                <HandCoins className="w-5 h-5" />
                लोकसेवा
              </Link>
              <Link to="/schemes" className="bg-white text-slate-900 px-6 py-3 rounded-md font-semibold hover:bg-slate-100 transition flex items-center gap-2" data-testid="hero-schemes-btn">
                <FileText className="w-5 h-5" />
                योजना माहिती
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Quick Links */}
      <section className="py-12 bg-white -mt-8 relative z-10">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {quickLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                className={`bg-gradient-to-br ${link.color} text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1`}
                data-testid={`quick-link-${link.path.slice(1)}`}
              >
                <link.icon className="w-8 h-8 mx-auto mb-3" />
                <p className="font-semibold">{link.label}</p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Sarpanch Message */}
      {sarpanch && (
        <section className="py-12 bg-slate-50">
          <div className="max-w-7xl mx-auto px-4">
            <div className="bg-white rounded-2xl shadow-lg overflow-hidden">
              <div className="md:flex">
                <div className="md:w-1/3 bg-slate-900 p-8 text-center flex flex-col items-center justify-center">
                  <div className="w-32 h-32 rounded-full bg-orange-500 flex items-center justify-center mb-4 border-4 border-orange-400">
                    <Users className="w-16 h-16 text-white" />
                  </div>
                  <h3 className="text-xl font-bold text-white">{sarpanch.name}</h3>
                  <p className="text-orange-400 font-semibold">सरपंच</p>
                  {sarpanch.phone && <p className="text-slate-400 text-sm mt-2"><Phone className="w-4 h-4 inline mr-1" />{sarpanch.phone}</p>}
                </div>
                <div className="md:w-2/3 p-8">
                  <h2 className="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <FileText className="w-6 h-6 text-orange-500" />
                    सरपंच मनोगत
                  </h2>
                  <p className="text-slate-600 leading-relaxed text-lg">
                    {settings?.sarpanch_message || 'आपल्या गावाच्या सर्वांगीण विकासासाठी आम्ही कटिबद्ध आहोत.'}
                  </p>
                  {sarpanch.bio && <p className="text-slate-500 mt-4 text-sm">{sarpanch.bio}</p>}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* News & Notices */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid lg:grid-cols-3 gap-8">
            {/* News */}
            <div className="lg:col-span-2">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                  <Newspaper className="w-6 h-6 text-orange-500" />
                  ताज्या बातम्या
                </h2>
                <Link to="/news" className="text-orange-500 hover:text-orange-600 font-semibold flex items-center gap-1">
                  सर्व पहा <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
              <div className="grid md:grid-cols-2 gap-6">
                {news?.slice(0, 4).map((item) => (
                  <article key={item.id} className="bg-white rounded-xl shadow-md overflow-hidden card-hover border border-slate-100">
                    <div className="h-48 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                      <Newspaper className="w-12 h-12 text-orange-400" />
                    </div>
                    <div className="p-5">
                      <p className="text-xs text-orange-500 font-semibold mb-2 flex items-center gap-1">
                        <Calendar className="w-3 h-3" />{item.published_date}
                      </p>
                      <h3 className="font-bold text-slate-900 mb-2 line-clamp-2">{item.title}</h3>
                      <p className="text-slate-600 text-sm line-clamp-2">{item.excerpt}</p>
                      <Link to={`/news/${item.id}`} className="text-orange-500 text-sm font-semibold mt-3 inline-flex items-center gap-1 hover:gap-2 transition-all">
                        अधिक वाचा <ArrowRight className="w-4 h-4" />
                      </Link>
                    </div>
                  </article>
                ))}
              </div>
            </div>

            {/* Notices */}
            <div>
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                  <Bell className="w-6 h-6 text-orange-500" />
                  नोटीस बोर्ड
                </h2>
                <Link to="/notices" className="text-orange-500 hover:text-orange-600 font-semibold text-sm flex items-center gap-1">
                  सर्व <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
              <div className="bg-white rounded-xl shadow-md p-4 max-h-[500px] overflow-y-auto border border-slate-100">
                {notices?.length > 0 ? notices.slice(0, 6).map((notice) => (
                  <Link 
                    key={notice.id} 
                    to={`/notices/${notice.id}`}
                    className="block p-3 border-b last:border-b-0 hover:bg-slate-50 transition rounded-lg"
                  >
                    <div className="flex items-start gap-3">
                      <div className={`w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${notice.is_important ? 'bg-red-100' : 'bg-blue-100'}`}>
                        <Bell className={`w-5 h-5 ${notice.is_important ? 'text-red-500' : 'text-blue-500'}`} />
                      </div>
                      <div>
                        <p className="font-semibold text-slate-900 text-sm line-clamp-2">{notice.title}</p>
                        <p className="text-xs text-slate-500 mt-1 flex items-center gap-1">
                          <Calendar className="w-3 h-3" />{notice.notice_date}
                          {notice.is_important && <span className="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs ml-2">महत्त्वाचे</span>}
                        </p>
                      </div>
                    </div>
                  </Link>
                )) : (
                  <p className="text-slate-500 text-center py-8">सध्या कोणतीही नोटीस नाही</p>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Schemes */}
      <section className="py-12 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-10">
            <h2 className="text-3xl font-bold text-slate-900 mb-2 flex items-center justify-center gap-2">
              <HandCoins className="w-8 h-8 text-orange-500" />
              शासकीय योजना
            </h2>
            <p className="text-slate-600">विविध शासकीय योजनांची माहिती मिळवा</p>
          </div>
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {schemes?.slice(0, 6).map((scheme) => (
              <div key={scheme.id} className="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-orange-500">
                <h3 className="font-bold text-slate-900 mb-3">{scheme.title}</h3>
                <p className="text-slate-600 text-sm line-clamp-3 mb-4">{scheme.description?.slice(0, 120)}...</p>
                <div className="flex items-center justify-between">
                  <span className="text-xs text-slate-500">{scheme.department}</span>
                  <Link to={`/schemes/${scheme.id}`} className="text-orange-500 font-semibold text-sm hover:text-orange-600 flex items-center gap-1">
                    माहिती पहा <ArrowRight className="w-4 h-4" />
                  </Link>
                </div>
              </div>
            ))}
          </div>
          <div className="text-center mt-8">
            <Link to="/schemes" className="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-md font-semibold hover:bg-slate-800 transition" data-testid="all-schemes-btn">
              सर्व योजना पहा <ArrowRight className="w-5 h-5" />
            </Link>
          </div>
        </div>
      </section>

      {/* Development Works */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-10">
            <h2 className="text-3xl font-bold text-slate-900 mb-2 flex items-center justify-center gap-2">
              <HardHat className="w-8 h-8 text-orange-500" />
              विकासकामे
            </h2>
            <p className="text-slate-600">गावातील सुरू असलेली आणि पूर्ण झालेली विकासकामे</p>
          </div>
          <div className="grid md:grid-cols-2 gap-6">
            {works?.slice(0, 4).map((work) => (
              <div key={work.id} className="bg-white rounded-xl shadow-md overflow-hidden card-hover border border-slate-100">
                <div className="p-6">
                  <div className="flex items-center gap-2 mb-3">
                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${statusColors[work.status]}`}>
                      {statusLabels[work.status]}
                    </span>
                  </div>
                  <h3 className="font-bold text-slate-900 mb-2">{work.title}</h3>
                  {work.location && <p className="text-slate-500 text-sm mb-2 flex items-center gap-1"><MapPin className="w-4 h-4" />{work.location}</p>}
                  {work.budget && <p className="text-slate-600 text-sm mb-4"><strong>अंदाजपत्रक:</strong> ₹{work.budget.toLocaleString()}</p>}
                  <div className="w-full bg-slate-200 rounded-full h-2.5 mb-2">
                    <div className="bg-orange-500 h-2.5 rounded-full progress-bar-animate" style={{ width: `${work.progress_percentage}%` }}></div>
                  </div>
                  <p className="text-xs text-slate-500">प्रगती: {work.progress_percentage}%</p>
                </div>
              </div>
            ))}
          </div>
          <div className="text-center mt-8">
            <Link to="/works" className="inline-flex items-center gap-2 bg-orange-500 text-white px-8 py-3 rounded-md font-semibold hover:bg-orange-600 transition" data-testid="all-works-btn">
              सर्व विकासकामे पहा <ArrowRight className="w-5 h-5" />
            </Link>
          </div>
        </div>
      </section>

      {/* Contact CTA */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4">
          <div className="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 className="text-2xl md:text-3xl font-bold mb-4">आमच्याशी संपर्क साधा</h2>
            <p className="text-lg mb-6 opacity-90">कोणतीही माहिती, तक्रार किंवा सूचना असल्यास आमच्याशी संपर्क करा</p>
            <Link to="/contact" className="inline-flex items-center gap-2 bg-white text-orange-600 px-8 py-3 rounded-md font-semibold hover:bg-slate-100 transition" data-testid="contact-cta-btn">
              <Mail className="w-5 h-5" />
              संपर्क करा
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

const ContactPage = () => {
  const [formData, setFormData] = useState({ name: '', email: '', phone: '', subject: '', message: '' });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await axios.post(`${API}/inquiries`, formData);
      toast.success('तुमचा संदेश यशस्वीरित्या पाठवला गेला!');
      setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
    } catch (error) {
      toast.error('संदेश पाठवताना त्रुटी झाली');
    }
    setLoading(false);
  };

  return (
    <div className="py-12" data-testid="contact-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">संपर्क करा</h1>
          <p className="text-slate-600">आम्हाला तुमचा संदेश पाठवा</p>
        </div>
        <div className="grid lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2">
            <div className="bg-white rounded-xl shadow-lg p-6 md:p-8 border border-slate-100">
              <h2 className="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <Mail className="w-6 h-6 text-orange-500" />
                संदेश पाठवा
              </h2>
              <form onSubmit={handleSubmit}>
                <div className="grid md:grid-cols-2 gap-6 mb-6">
                  <div>
                    <label className="block text-slate-700 font-semibold mb-2">नाव <span className="text-red-500">*</span></label>
                    <input
                      type="text"
                      required
                      value={formData.name}
                      onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                      className="w-full px-4 py-3 border border-slate-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                      placeholder="तुमचे नाव"
                      data-testid="contact-name-input"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-700 font-semibold mb-2">मोबाईल नंबर <span className="text-red-500">*</span></label>
                    <input
                      type="tel"
                      required
                      value={formData.phone}
                      onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                      className="w-full px-4 py-3 border border-slate-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                      placeholder="9876543210"
                      data-testid="contact-phone-input"
                    />
                  </div>
                </div>
                <div className="mb-6">
                  <label className="block text-slate-700 font-semibold mb-2">ईमेल (ऐच्छिक)</label>
                  <input
                    type="email"
                    value={formData.email}
                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    className="w-full px-4 py-3 border border-slate-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                    placeholder="email@example.com"
                    data-testid="contact-email-input"
                  />
                </div>
                <div className="mb-6">
                  <label className="block text-slate-700 font-semibold mb-2">विषय <span className="text-red-500">*</span></label>
                  <input
                    type="text"
                    required
                    value={formData.subject}
                    onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                    className="w-full px-4 py-3 border border-slate-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                    placeholder="संदेशाचा विषय"
                    data-testid="contact-subject-input"
                  />
                </div>
                <div className="mb-6">
                  <label className="block text-slate-700 font-semibold mb-2">संदेश <span className="text-red-500">*</span></label>
                  <textarea
                    required
                    rows={5}
                    value={formData.message}
                    onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                    className="w-full px-4 py-3 border border-slate-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition resize-none"
                    placeholder="तुमचा संदेश येथे लिहा..."
                    data-testid="contact-message-input"
                  />
                </div>
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full md:w-auto bg-orange-500 text-white px-8 py-3 rounded-md font-semibold hover:bg-orange-600 transition disabled:opacity-50 flex items-center justify-center gap-2"
                  data-testid="contact-submit-btn"
                >
                  <Mail className="w-5 h-5" />
                  {loading ? 'पाठवत आहे...' : 'संदेश पाठवा'}
                </button>
              </form>
            </div>
          </div>
          <div className="space-y-6">
            <div className="bg-white rounded-xl shadow-lg p-6 border border-slate-100">
              <h3 className="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2"><MapPin className="w-5 h-5 text-orange-500" />पत्ता</h3>
              <p className="text-slate-600">ग्रामपंचायत कार्यालय, आदर्शगाव, तालुका - xyz, जिल्हा - xyz, महाराष्ट्र</p>
            </div>
            <div className="bg-white rounded-xl shadow-lg p-6 border border-slate-100">
              <h3 className="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2"><Phone className="w-5 h-5 text-orange-500" />फोन</h3>
              <p className="text-slate-600">02XX-XXXXXX</p>
            </div>
            <div className="bg-white rounded-xl shadow-lg p-6 border border-slate-100">
              <h3 className="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2"><Mail className="w-5 h-5 text-orange-500" />ईमेल</h3>
              <p className="text-slate-600">grampanchayat@gov.in</p>
            </div>
            <div className="bg-white rounded-xl shadow-lg p-6 border border-slate-100">
              <h3 className="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2"><Clock className="w-5 h-5 text-orange-500" />कार्यालयीन वेळ</h3>
              <p className="text-slate-600">सोमवार - शनिवार<br/>सकाळी 10:00 - संध्याकाळी 5:00<br/><span className="text-red-500">रविवार बंद</span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

const MembersPage = ({ members }) => {
  const designationLabels = {
    sarpanch: 'सरपंच',
    up_sarpanch: 'उपसरपंच',
    gram_sevak: 'ग्रामसेवक',
    member: 'सदस्य',
    secretary: 'सचिव'
  };

  return (
    <div className="py-12" data-testid="members-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">ग्रामपंचायत सदस्य</h1>
          <p className="text-slate-600">निर्वाचित प्रतिनिधी आणि अधिकारी</p>
        </div>
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {members?.map((member) => (
            <div key={member.id} className="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-slate-100">
              <div className="bg-gradient-to-r from-slate-800 to-slate-900 p-6 text-center">
                <div className="w-24 h-24 rounded-full bg-orange-500 flex items-center justify-center mx-auto mb-4 border-4 border-orange-400">
                  <Users className="w-12 h-12 text-white" />
                </div>
                <h3 className="text-xl font-bold text-white">{member.name}</h3>
                <p className="text-orange-400 font-semibold">{designationLabels[member.designation] || member.designation}</p>
                {member.ward_number && <p className="text-slate-400 text-sm">{member.ward_number}</p>}
              </div>
              <div className="p-5">
                {member.bio && <p className="text-slate-600 text-sm mb-4 line-clamp-3">{member.bio}</p>}
                <div className="space-y-2 text-sm">
                  {member.phone && (
                    <p className="flex items-center gap-2 text-slate-600">
                      <Phone className="w-4 h-4 text-orange-500" />
                      <a href={`tel:${member.phone}`} className="hover:text-orange-500">{member.phone}</a>
                    </p>
                  )}
                  {member.email && (
                    <p className="flex items-center gap-2 text-slate-600">
                      <Mail className="w-4 h-4 text-orange-500" />
                      <a href={`mailto:${member.email}`} className="hover:text-orange-500">{member.email}</a>
                    </p>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const SchemesPage = ({ schemes }) => {
  return (
    <div className="py-12" data-testid="schemes-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">शासकीय योजना</h1>
          <p className="text-slate-600">विविध शासकीय योजनांची माहिती</p>
        </div>
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {schemes?.map((scheme) => (
            <div key={scheme.id} className="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-orange-500">
              <h3 className="font-bold text-slate-900 mb-3 text-lg">{scheme.title}</h3>
              <p className="text-slate-600 text-sm mb-4 line-clamp-3">{scheme.description}</p>
              {scheme.department && <p className="text-xs text-slate-500 mb-4">{scheme.department}</p>}
              <div className="flex items-center justify-between">
                {scheme.gr_link && (
                  <a href={scheme.gr_link} target="_blank" rel="noopener noreferrer" className="text-blue-500 text-sm flex items-center gap-1 hover:underline">
                    <ExternalLink className="w-4 h-4" /> GR पहा
                  </a>
                )}
                <Link to={`/schemes/${scheme.id}`} className="text-orange-500 font-semibold text-sm hover:text-orange-600 flex items-center gap-1">
                  संपूर्ण माहिती <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const WorksPage = ({ works }) => {
  const statusLabels = { planned: 'नियोजित', in_progress: 'प्रगतीपथावर', completed: 'पूर्ण', on_hold: 'स्थगित' };
  const statusColors = { planned: 'bg-blue-100 text-blue-800', in_progress: 'bg-yellow-100 text-yellow-800', completed: 'bg-green-100 text-green-800', on_hold: 'bg-red-100 text-red-800' };

  return (
    <div className="py-12" data-testid="works-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">विकासकामे</h1>
          <p className="text-slate-600">गावातील विकास प्रकल्प</p>
        </div>
        <div className="grid md:grid-cols-2 gap-6">
          {works?.map((work) => (
            <div key={work.id} className="bg-white rounded-xl shadow-md overflow-hidden card-hover border border-slate-100">
              <div className="p-6">
                <div className="flex items-center gap-2 mb-3">
                  <span className={`px-3 py-1 rounded-full text-xs font-semibold ${statusColors[work.status]}`}>{statusLabels[work.status]}</span>
                </div>
                <h3 className="font-bold text-slate-900 mb-2 text-lg">{work.title}</h3>
                {work.description && <p className="text-slate-600 text-sm mb-3">{work.description}</p>}
                {work.location && <p className="text-slate-500 text-sm mb-2 flex items-center gap-1"><MapPin className="w-4 h-4" />{work.location}</p>}
                <div className="grid grid-cols-2 gap-4 text-sm mb-4">
                  {work.budget && <p><strong>अंदाजपत्रक:</strong> ₹{work.budget.toLocaleString()}</p>}
                  {work.spent_amount && <p><strong>खर्च:</strong> ₹{work.spent_amount.toLocaleString()}</p>}
                  {work.contractor_name && <p className="col-span-2"><strong>ठेकेदार:</strong> {work.contractor_name}</p>}
                </div>
                <div className="w-full bg-slate-200 rounded-full h-3 mb-2">
                  <div className="bg-orange-500 h-3 rounded-full transition-all duration-1000" style={{ width: `${work.progress_percentage}%` }}></div>
                </div>
                <p className="text-sm text-slate-600 font-semibold">प्रगती: {work.progress_percentage}%</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const ServicesPage = ({ services }) => {
  return (
    <div className="py-12" data-testid="services-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">लोकसेवा</h1>
          <p className="text-slate-600">ग्रामपंचायत द्वारे पुरविल्या जाणाऱ्या सेवा</p>
        </div>
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {services?.map((service) => (
            <div key={service.id} className="bg-white rounded-xl shadow-md p-6 card-hover border border-slate-100">
              <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                <FileText className="w-6 h-6 text-orange-500" />
              </div>
              <h3 className="font-bold text-slate-900 mb-3 text-lg">{service.title}</h3>
              <p className="text-slate-600 text-sm mb-4">{service.description}</p>
              <div className="space-y-2 text-sm text-slate-600">
                {service.fees && <p><strong>शुल्क:</strong> {service.fees}</p>}
                {service.time_duration && <p><strong>कालावधी:</strong> {service.time_duration}</p>}
              </div>
              <Link to={`/services/${service.id}`} className="mt-4 inline-flex items-center gap-1 text-orange-500 font-semibold text-sm hover:text-orange-600">
                अधिक माहिती <ArrowRight className="w-4 h-4" />
              </Link>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const NoticesPage = ({ notices }) => {
  return (
    <div className="py-12" data-testid="notices-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">नोटीस बोर्ड</h1>
          <p className="text-slate-600">महत्त्वाच्या सूचना आणि जाहिराती</p>
        </div>
        <div className="space-y-4">
          {notices?.map((notice) => (
            <div key={notice.id} className={`bg-white rounded-xl shadow-md p-6 card-hover border-l-4 ${notice.is_important ? 'border-red-500' : 'border-orange-500'}`}>
              <div className="flex items-start justify-between gap-4">
                <div className="flex-1">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="text-sm text-slate-500 flex items-center gap-1"><Calendar className="w-4 h-4" />{notice.notice_date}</span>
                    {notice.is_important && <span className="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-semibold">महत्त्वाचे</span>}
                  </div>
                  <h3 className="font-bold text-slate-900 text-lg mb-2">{notice.title}</h3>
                  {notice.description && <p className="text-slate-600">{notice.description}</p>}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const NewsPage = ({ news }) => {
  return (
    <div className="py-12" data-testid="news-page">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2">बातम्या</h1>
          <p className="text-slate-600">ताज्या बातम्या आणि घडामोडी</p>
        </div>
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {news?.map((item) => (
            <article key={item.id} className="bg-white rounded-xl shadow-md overflow-hidden card-hover border border-slate-100">
              <div className="h-48 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                <Newspaper className="w-12 h-12 text-orange-400" />
              </div>
              <div className="p-5">
                <p className="text-xs text-orange-500 font-semibold mb-2 flex items-center gap-1">
                  <Calendar className="w-3 h-3" />{item.published_date}
                  {item.is_featured && <span className="bg-orange-100 text-orange-600 px-2 py-0.5 rounded text-xs ml-2">वैशिष्ट्यीकृत</span>}
                </p>
                <h3 className="font-bold text-slate-900 mb-2 line-clamp-2">{item.title}</h3>
                <p className="text-slate-600 text-sm line-clamp-3">{item.excerpt || item.content?.slice(0, 100)}</p>
                <div className="flex items-center justify-between mt-4">
                  <span className="text-xs text-slate-500 flex items-center gap-1"><Eye className="w-3 h-3" />{item.views} views</span>
                  <Link to={`/news/${item.id}`} className="text-orange-500 text-sm font-semibold hover:text-orange-600 flex items-center gap-1">
                    अधिक वाचा <ArrowRight className="w-4 h-4" />
                  </Link>
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>
    </div>
  );
};

const AboutPage = () => {
  return (
    <div className="py-12" data-testid="about-page">
      <div className="max-w-4xl mx-auto px-4">
        <h1 className="text-3xl md:text-4xl font-bold text-slate-900 mb-6">आमच्याबद्दल</h1>
        <div className="prose prose-lg max-w-none">
          <h2 className="text-2xl font-bold text-slate-800 mb-4">ग्रामपंचायत आदर्शगाव</h2>
          <p className="text-slate-600 mb-4">आदर्शगाव हे महाराष्ट्रातील एक प्रगतीशील गाव आहे. आमच्या ग्रामपंचायतीची स्थापना 1960 साली झाली. गेल्या अनेक वर्षांत आमच्या गावाने विविध क्षेत्रात उल्लेखनीय प्रगती केली आहे.</p>
          <h3 className="text-xl font-bold text-slate-800 mb-3 mt-6">आमची दृष्टी</h3>
          <p className="text-slate-600 mb-4">स्वच्छ, सुंदर आणि समृद्ध गाव निर्माण करणे.</p>
          <h3 className="text-xl font-bold text-slate-800 mb-3">आमचे ध्येय</h3>
          <ul className="list-disc list-inside text-slate-600 space-y-2">
            <li>प्रत्येक नागरिकाला मूलभूत सुविधा पुरविणे</li>
            <li>शिक्षण आणि आरोग्य क्षेत्रात सुधारणा करणे</li>
            <li>रोजगाराच्या संधी निर्माण करणे</li>
            <li>पर्यावरण संवर्धन करणे</li>
          </ul>
        </div>
      </div>
    </div>
  );
};

// ============== ADMIN PAGES ==============

const AdminLogin = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const response = await axios.post(`${API}/auth/login`, { email, password });
      login(response.data.admin);
      toast.success('लॉगिन यशस्वी!');
      navigate('/admin/dashboard');
    } catch (error) {
      toast.error(error.response?.data?.detail || 'लॉगिन अयशस्वी');
    }
    setLoading(false);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center p-4" data-testid="admin-login-page">
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <div className="w-20 h-20 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
            <Landmark className="w-10 h-10 text-white" />
          </div>
          <h1 className="text-2xl font-bold text-white">ग्रामपंचायत आदर्शगाव</h1>
          <p className="text-slate-400">प्रशासक पॅनेल लॉगिन</p>
        </div>
        <div className="bg-white rounded-2xl shadow-xl p-8">
          <form onSubmit={handleSubmit}>
            <div className="mb-5">
              <label className="block text-slate-700 font-semibold mb-2"><Mail className="w-4 h-4 inline mr-1 text-orange-500" />ईमेल</label>
              <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required className="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500" placeholder="admin@example.com" data-testid="admin-email-input" />
            </div>
            <div className="mb-5">
              <label className="block text-slate-700 font-semibold mb-2"><Shield className="w-4 h-4 inline mr-1 text-orange-500" />पासवर्ड</label>
              <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} required className="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500" placeholder="••••••••" data-testid="admin-password-input" />
            </div>
            <button type="submit" disabled={loading} className="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition disabled:opacity-50" data-testid="admin-login-btn">
              {loading ? 'लॉगिन करत आहे...' : 'लॉगिन करा'}
            </button>
          </form>
        </div>
        <div className="mt-6 bg-slate-700/50 rounded-lg p-4 text-center">
          <p className="text-slate-300 text-sm mb-2">Demo Login:</p>
          <p className="text-slate-400 text-xs">Email: admin@grampanchayat.gov.in</p>
          <p className="text-slate-400 text-xs">Password: Admin@123</p>
        </div>
        <div className="text-center mt-4">
          <Link to="/" className="text-slate-400 hover:text-white transition">← वेबसाईटवर परत जा</Link>
        </div>
      </div>
    </div>
  );
};

const AdminDashboard = () => {
  const { admin, logout } = useAuth();
  const navigate = useNavigate();
  const [stats, setStats] = useState({});

  useEffect(() => {
    if (!admin) {
      navigate('/admin');
      return;
    }
    fetchStats();
  }, [admin, navigate]);

  const fetchStats = async () => {
    try {
      const response = await axios.get(`${API}/admin/stats`);
      setStats(response.data);
    } catch (error) {
      console.error('Error fetching stats:', error);
    }
  };

  const handleLogout = () => {
    logout();
    navigate('/admin');
    toast.success('लॉगआउट यशस्वी');
  };

  if (!admin) return null;

  return (
    <div className="min-h-screen bg-slate-100" data-testid="admin-dashboard">
      {/* Sidebar */}
      <aside className="fixed left-0 top-0 w-64 h-full bg-slate-900 text-white z-40">
        <div className="p-4 border-b border-slate-700">
          <Link to="/admin/dashboard" className="flex items-center gap-3">
            <div className="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
              <Landmark className="w-5 h-5 text-white" />
            </div>
            <div>
              <h1 className="font-bold">ग्रामपंचायत</h1>
              <p className="text-xs text-slate-400">प्रशासन पॅनेल</p>
            </div>
          </Link>
        </div>
        <nav className="p-4 space-y-1">
          <Link to="/admin/dashboard" className="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-orange-500 text-white">
            <LayoutDashboard className="w-5 h-5" />डॅशबोर्ड
          </Link>
          <Link to="/" target="_blank" className="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
            <ExternalLink className="w-5 h-5" />वेबसाईट पहा
          </Link>
        </nav>
        <div className="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700">
          <div className="flex items-center gap-3 mb-3">
            <div className="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
              <Users className="w-5 h-5 text-white" />
            </div>
            <div>
              <p className="font-semibold text-sm">{admin.name}</p>
              <p className="text-xs text-slate-400">{admin.role === 'super_admin' ? 'Super Admin' : 'Editor'}</p>
            </div>
          </div>
          <button onClick={handleLogout} className="w-full flex items-center justify-center gap-2 px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-lg transition" data-testid="admin-logout-btn">
            <LogOut className="w-4 h-4" />लॉगआउट
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <div className="ml-64 p-6">
        <div className="mb-6">
          <h1 className="text-2xl font-bold text-slate-800">डॅशबोर्ड</h1>
          <p className="text-slate-600">स्वागत आहे, {admin.name}</p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
          <div className="bg-white rounded-xl shadow p-5">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-slate-500 text-sm">नोटीस</p>
                <p className="text-2xl font-bold text-slate-800">{stats.notices || 0}</p>
              </div>
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Bell className="w-6 h-6 text-blue-500" />
              </div>
            </div>
          </div>
          <div className="bg-white rounded-xl shadow p-5">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-slate-500 text-sm">बातम्या</p>
                <p className="text-2xl font-bold text-slate-800">{stats.news || 0}</p>
              </div>
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Newspaper className="w-6 h-6 text-green-500" />
              </div>
            </div>
          </div>
          <div className="bg-white rounded-xl shadow p-5">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-slate-500 text-sm">योजना</p>
                <p className="text-2xl font-bold text-slate-800">{stats.schemes || 0}</p>
              </div>
              <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <HandCoins className="w-6 h-6 text-yellow-500" />
              </div>
            </div>
          </div>
          <div className="bg-white rounded-xl shadow p-5">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-slate-500 text-sm">नवीन चौकशी</p>
                <p className="text-2xl font-bold text-slate-800">{stats.inquiries_new || 0}</p>
              </div>
              <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <Mail className="w-6 h-6 text-red-500" />
              </div>
            </div>
          </div>
        </div>

        {/* Visitor Stats */}
        <div className="grid md:grid-cols-3 gap-4 mb-8">
          <div className="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow p-5 text-white">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-orange-100 text-sm">एकूण भेटीगार</p>
                <p className="text-3xl font-bold">{stats.total_visitors?.toLocaleString() || 0}</p>
              </div>
              <Users className="w-12 h-12 text-orange-200" />
            </div>
          </div>
          <div className="bg-gradient-to-r from-slate-700 to-slate-800 rounded-xl shadow p-5 text-white">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-slate-300 text-sm">विकासकामे</p>
                <p className="text-3xl font-bold">{stats.works || 0}</p>
              </div>
              <HardHat className="w-12 h-12 text-slate-500" />
            </div>
          </div>
          <div className="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow p-5 text-white">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-emerald-100 text-sm">सक्रिय</p>
                <p className="text-3xl font-bold">✓</p>
              </div>
              <Shield className="w-12 h-12 text-emerald-200" />
            </div>
          </div>
        </div>

        {/* Info Box */}
        <div className="bg-white rounded-xl shadow p-6">
          <h2 className="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <Settings className="w-5 h-5 text-orange-500" />
            Admin Panel माहिती
          </h2>
          <p className="text-slate-600 mb-4">
            हे Admin Dashboard आहे. येथून तुम्ही नोटीस, बातम्या, योजना आणि इतर सामग्री व्यवस्थापित करू शकता.
          </p>
          <div className="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <p className="text-orange-800 text-sm">
              <strong>सध्या उपलब्ध:</strong> Dashboard stats, Logout functionality<br/>
              <strong>पुढील आवृत्तीत:</strong> Full CRUD operations for all modules
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

// ============== MAIN APP ==============

const AppContent = () => {
  const [settings, setSettings] = useState({});
  const [notices, setNotices] = useState([]);
  const [news, setNews] = useState([]);
  const [schemes, setSchemes] = useState([]);
  const [works, setWorks] = useState([]);
  const [services, setServices] = useState([]);
  const [members, setMembers] = useState([]);
  const [visitorCount, setVisitorCount] = useState({ total: 0 });
  const location = useLocation();

  useEffect(() => {
    seedAndFetchData();
    trackVisitor();
  }, []);

  const seedAndFetchData = async () => {
    try {
      // Seed database first
      await axios.post(`${API}/seed`);
      // Then fetch all data
      fetchAllData();
    } catch (error) {
      console.error('Error seeding/fetching:', error);
      fetchAllData();
    }
  };

  const fetchAllData = async () => {
    try {
      const [settingsRes, noticesRes, newsRes, schemesRes, worksRes, servicesRes, membersRes, visitorsRes] = await Promise.all([
        axios.get(`${API}/settings`),
        axios.get(`${API}/notices?published_only=true`),
        axios.get(`${API}/news?published_only=true`),
        axios.get(`${API}/schemes?published_only=true`),
        axios.get(`${API}/works?published_only=true`),
        axios.get(`${API}/services?published_only=true`),
        axios.get(`${API}/members?active_only=true`),
        axios.get(`${API}/visitors/count`),
      ]);
      setSettings(settingsRes.data);
      setNotices(noticesRes.data);
      setNews(newsRes.data);
      setSchemes(schemesRes.data);
      setWorks(worksRes.data);
      setServices(servicesRes.data);
      setMembers(membersRes.data);
      setVisitorCount(visitorsRes.data);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };

  const trackVisitor = async () => {
    try {
      await axios.post(`${API}/visitors/track`);
    } catch (error) {
      console.error('Error tracking visitor:', error);
    }
  };

  const isAdminRoute = location.pathname.startsWith('/admin');

  return (
    <div className="min-h-screen flex flex-col">
      {!isAdminRoute && (
        <>
          <Header settings={settings} />
          <NoticeTicker notices={notices.filter(n => n.show_in_ticker)} />
        </>
      )}
      
      <main className="flex-1">
        <Routes>
          <Route path="/" element={<HomePage notices={notices} news={news} schemes={schemes} works={works} members={members} settings={settings} />} />
          <Route path="/contact" element={<ContactPage />} />
          <Route path="/members" element={<MembersPage members={members} />} />
          <Route path="/schemes" element={<SchemesPage schemes={schemes} />} />
          <Route path="/works" element={<WorksPage works={works} />} />
          <Route path="/services" element={<ServicesPage services={services} />} />
          <Route path="/notices" element={<NoticesPage notices={notices} />} />
          <Route path="/news" element={<NewsPage news={news} />} />
          <Route path="/about" element={<AboutPage />} />
          <Route path="/gallery" element={<div className="py-12 text-center"><h1 className="text-3xl font-bold">फोटो गॅलरी</h1><p className="text-slate-600 mt-2">लवकरच येत आहे...</p></div>} />
          <Route path="/admin" element={<AdminLogin />} />
          <Route path="/admin/dashboard" element={<AdminDashboard />} />
        </Routes>
      </main>
      
      {!isAdminRoute && <Footer settings={settings} visitorCount={visitorCount} />}
    </div>
  );
};

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Toaster position="top-right" richColors />
        <AppContent />
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
