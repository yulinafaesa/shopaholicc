import { Link, router, usePage } from '@inertiajs/react'
import { LogOut, Menu, X } from 'lucide-react'
import { useState } from 'react'
import { themeCss } from '../theme/shopaholicTheme'

const logoPath = '/img/Logo%20Shopaholic%203.png'

const navItems = [
    { label: 'Belanja', href: '/pembeli/belanja' },
    { label: 'Rute Jastip', href: '/pembeli/rute' },
    { label: 'Tracking', href: '/pembeli/tracking' },
    { label: 'Keranjang', href: '/pembeli/keranjang' },
    { label: 'Testimoni', href: '/pembeli/testimoni' },
    { label: 'Akun', href: '/pembeli/akun' },
]

export default function PembeliLayout({
    children,
    title,
    label = 'AREA PEMBELI',
    description,
    headerAction,
}) {
    const page = usePage()
    const { cart, auth } = page.props
    const currentUrl = typeof page.url === 'string' ? page.url : ''
    const cartCount = cart?.count ?? 0
    const [menuOpen, setMenuOpen] = useState(false)

    const isActive = (href) => {
        if (!href || !currentUrl) return false;

        return currentUrl === href || currentUrl.startsWith(href + '/');
    }

    const handleLogout = () => router.post('/keluar')

    return (
        <div
            style={{
                minHeight: '100vh',
                backgroundColor: 'var(--bg)',
                color: 'var(--ink)',
            }}
        >
            <style>{`
                ${themeCss}
                .pembeli-logout-btn {
                    padding: 8px 14px;
                    border: 1px solid rgba(255, 255, 255, 0.4);
                    color: #ffffff;
                    background: rgba(255, 255, 255, 0.08);
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 600;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                .pembeli-logout-btn:hover {
                    background: rgba(255, 255, 255, 0.2);
                    border-color: #ffffff;
                    transform: translateY(-1px);
                }
                .pembeli-menu-toggle {
                    display: none;
                    background: transparent;
                    border: none;
                    color: #ffffff;
                    cursor: pointer;
                    align-items: center;
                    justify-content: center;
                    padding: 8px;
                    border-radius: 8px;
                    transition: background 0.2s;
                }
                .pembeli-menu-toggle:hover {
                    background: rgba(255, 255, 255, 0.1);
                }
                
                @media (max-width: 1024px) {
                    .pembeli-nav-center {
                        display: none !important;
                    }
                    .pembeli-menu-toggle {
                        display: flex;
                    }
                    .pembeli-nav-right {
                        gap: 8px !important;
                    }
                }
            `}</style>

            <nav
                style={{
                    position: 'sticky',
                    top: 0,
                    zIndex: 30,
                    backdropFilter: 'blur(12px)',
                    WebkitBackdropFilter: 'blur(12px)',
                    background:
                        'linear-gradient(90deg,#800020,#6D0019,#4A0012)',
                    borderBottom: '1px solid #5c0015',
                }}
            >
                <div
                    className="pembeli-container"
                    style={{
                        height: 74,
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        gap: 16,
                    }}
                >
                    <Link
                        href="/pembeli/belanja"
                        style={{
                            textDecoration: 'none',
                            display: 'inline-flex',
                            alignItems: 'center',
                        }}
                    >
                        <img
                            src={logoPath}
                            alt="Shopaholic"
                            style={{
                                height: 52,
                                width: 'auto',
                                display: 'block',
                                objectFit: 'contain',
                            }}
                        />
                    </Link>

                    <div
                        className="pembeli-nav-center"
                        style={{
                            display: 'flex',
                            gap: 'clamp(12px, 2vw, 24px)',
                            alignItems: 'center',
                        }}
                    >
                        {navItems.map((item) => (
                            <Link
                                key={item.href}
                                href={item.href}
                                className="pembeli-nav-link"
                                style={{
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: 6,
                                    textDecoration: 'none',
                                    fontSize: 13,
                                    color: '#ffffff',
                                    borderBottom: isActive(item.href)
                                        ? '2px solid #ffffff'
                                        : '2px solid transparent',
                                    paddingBottom: 6,
                                    fontWeight: isActive(item.href)
                                        ? '700'
                                        : '500',
                                }}
                            >
                                {item.label}

                                {item.href === '/pembeli/keranjang' &&
                                    cartCount > 0 && (
                                        <span
                                            style={{
                                                background: '#ffffff',
                                                color: '#800020',
                                                fontSize: 10,
                                                fontWeight: 700,
                                                minWidth: 18,
                                                height: 18,
                                                borderRadius: 9,
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                justifyContent: 'center',
                                                padding: '0 5px',
                                                fontFamily:
                                                    'var(--font-body)',
                                            }}
                                        >
                                            {cartCount}
                                        </span>
                                    )}
                            </Link>
                        ))}
                    </div>

                    <div
                        className="pembeli-nav-right"
                        style={{
                            display: 'flex',
                            alignItems: 'center',
                            gap: 12,
                        }}
                    >
                        {auth?.user?.name && (
                            <span
                                style={{
                                    fontSize: 12,
                                    color: '#ffffff',
                                    maxWidth: 120,
                                    overflow: 'hidden',
                                    textOverflow: 'ellipsis',
                                    whiteSpace: 'nowrap',
                                    opacity: 0.9,
                                }}
                            >
                                {auth.user.name}
                            </span>
                        )}

                        <button
                            type="button"
                            onClick={handleLogout}
                            className="pembeli-logout-btn"
                            aria-label="Keluar"
                        >
                            <LogOut size={14} />
                            <span>Keluar</span>
                        </button>
                        
                        <button
                            type="button"
                            onClick={() => setMenuOpen(!menuOpen)}
                            className="pembeli-menu-toggle"
                            aria-label="Toggle Menu"
                        >
                            {menuOpen ? <X size={24} /> : <Menu size={24} />}
                        </button>
                    </div>
                </div>

                {/* Mobile Dropdown Menu */}
                {menuOpen && (
                    <div
                        style={{
                            background: 'linear-gradient(180deg, #6D0019, #4A0012)',
                            borderTop: '1px solid #5c0015',
                            padding: '12px 32px',
                            display: 'flex',
                            flexDirection: 'column',
                            gap: 12,
                        }}
                    >
                        {navItems.map((item) => (
                            <Link
                                key={item.href}
                                href={item.href}
                                onClick={() => setMenuOpen(false)}
                                style={{
                                    textDecoration: 'none',
                                    fontSize: 14,
                                    color: '#ffffff',
                                    padding: '8px 0',
                                    fontWeight: isActive(item.href) ? '700' : '500',
                                    borderBottom: isActive(item.href) ? '1px solid #ffffff' : '1px solid transparent',
                                    display: 'flex',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                }}
                            >
                                <span>{item.label}</span>
                                {item.href === '/pembeli/keranjang' && cartCount > 0 && (
                                    <span
                                        style={{
                                            background: '#ffffff',
                                            color: '#800020',
                                            fontSize: 10,
                                            fontWeight: 700,
                                            minWidth: 18,
                                            height: 18,
                                            borderRadius: 9,
                                            display: 'inline-flex',
                                            alignItems: 'center',
                                            justifyContent: 'center',
                                            padding: '0 5px',
                                        }}
                                    >
                                        {cartCount}
                                    </span>
                                )}
                            </Link>
                        ))}
                    </div>
                )}
            </nav>

            {(title || description) && (
                <header
                    style={{
                        borderBottom: '1px solid var(--border)',
                        background:
                            'linear-gradient(135deg,#FFF4F8,#FFFBEF,#F7F2FF)',
                        padding: '60px 0 50px',
                    }}
                >
                    <div className="pembeli-container">
                        <div
                            style={{
                                display: 'flex',
                                flexWrap: 'wrap',
                                alignItems: 'flex-end',
                                justifyContent: 'space-between',
                                gap: 16,
                            }}
                        >
                            <div>
                                {label && (
                                    <p className="pembeli-label">{label}</p>
                                )}

                                {title && (
                                    <h1
                                        style={{
                                            margin: label ? '10px 0 0' : 0,
                                            fontFamily:
                                                'var(--font-heading)',
                                            fontSize:
                                                'clamp(28px, 5vw, 38px)',
                                            fontWeight: 600,
                                            lineHeight: 1.15,
                                        }}
                                    >
                                        {title}
                                    </h1>
                                )}

                                {description && (
                                    <p
                                        style={{
                                            margin: '12px 0 0',
                                            color: 'var(--muted)',
                                            fontSize: 14,
                                            lineHeight: 1.7,
                                            maxWidth: 560,
                                        }}
                                    >
                                        {description}
                                    </p>
                                )}
                            </div>

                            {headerAction}
                        </div>
                    </div>
                </header>
            )}

            <main style={{ padding: '32px 0 56px', flex: 1 }}>
                <div className="pembeli-container">{children}</div>
            </main>

            <footer
                style={{
                    borderTop: '1px solid var(--border)',
                    padding: '20px 0',
                    background: 'var(--bg)',
                }}
            >
                <div
                    className="pembeli-container"
                    style={{
                        display: 'flex',
                        flexWrap: 'wrap',
                        justifyContent: 'space-between',
                        gap: 12,
                        fontSize: 12,
                        color: 'var(--muted)',
                    }}
                >
                    <span>© 2026 Shopaholic — Jasa Titip Terpercaya</span>

                    <Link
                        href="/"
                        style={{
                            color: 'var(--primary)',
                            textDecoration: 'none',
                        }}
                    >
                        Kembali ke Beranda
                    </Link>
                </div>
            </footer>
        </div>
    )
}