export const theme = {
    bg: '#FFF4F8',
    card: '#FFFFFF',

    cream: '#FFF7FA',
    border: '#EFD8E4',

    primary: '#D63384',
    primaryHover: '#C2186A',

    gold: '#FFB84D',

    ink: '#2B1E24',
    muted: '#8B6B79',

    shadow: '0 12px 30px rgba(214,51,132,.08)',

    fontHeading: '"Antic Didone", serif',
    fontBody: '"Plus Jakarta Sans", sans-serif',
}

export const themeCss = `
:root{
    --bg:#FFF4F8;
    --card:#FFFFFF;
    --cream:#FFF7FA;
    --border:#EFD8E4;

    --primary:#D63384;
    --primary-hover:#C2186A;

    --gold:#FFB84D;

    --ink:#2B1E24;
    --muted:#8B6B79;

    --shadow-soft:0 12px 30px rgba(214,51,132,.08);

    --font-heading:"Antic Didone",serif;
    --font-body:"Plus Jakarta Sans",sans-serif;
}

*, *::before, *::after {
    box-sizing: border-box;
}

html, body {
    max-width: 100%;
    overflow-x: hidden;
}

body{
    background:
        linear-gradient(
            135deg,
            #FFF4F8 0%,
            #FFFBEF 50%,
            #F7F2FF 100%
        );
}

.pembeli-container{
    width:min(1280px,100%);
    margin:0 auto;
    padding:0 32px;
    box-sizing:border-box;
}

.pembeli-label{
    color:var(--primary);
    font-size:12px;
    letter-spacing:4px;
    text-transform:uppercase;
    font-weight:700;
}

.pembeli-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;

    border:none;
    border-radius:14px;

    padding:12px 18px;

    cursor:pointer;
    transition:.25s;
    font-family:var(--font-body);
}

.pembeli-btn:hover{
    transform:translateY(-2px);
}

.pembeli-btn-primary{
    background:linear-gradient(
        135deg,
        #D63384,
        #F06292
    );

    color:white;
}

.pembeli-btn-outline{
    border:1px solid #E6C9D8;
    background:white;
    color:var(--primary);
}

.pembeli-btn-outline:hover{
    background:#FFF1F7;
}

.pembeli-btn-ghost{
    background:white;
    border:1px solid var(--border);
    color:var(--muted);
}

.pembeli-card{
    background:white;

    border:1px solid #F0DCE7;

    border-radius:22px;

    box-shadow:
        0 12px 32px rgba(214,51,132,.06);
}

.pembeli-input{
    width:100%;

    border:none;

    border-radius:18px;

    background:#FAFAFA;

    padding:14px 18px;

    outline:none;

    font-size:14px;
}

.pembeli-input:focus{
    box-shadow:
        0 0 0 3px rgba(214,51,132,.15);
}

.pembeli-nav-link{
    color:var(--muted);
    text-decoration:none;
    transition:.2s;
}

.pembeli-nav-link:hover{
    color:var(--primary);
}

.pembeli-nav-link.is-active{
    color:var(--primary);
}

@media(max-width:1024px){
    .pembeli-nav-center{
        display:none;
    }

    .pembeli-nav-mobile{
        display:flex !important;
    }
}
`

export const penjualLayoutCss = `
.penjual-shell{
    display:flex;
    min-height:100vh;
    background:linear-gradient(
        135deg,
        #FFF4F8,
        #FFFBEF,
        #F7F2FF
    );
}

.penjual-sidebar{
    width:250px;

    background:white;

    border-right:1px solid #F0DCE7;
}
    .penjual-main{
    flex:1;
    display:flex;
    flex-direction:column;
    min-width:0;
}

.penjual-nav-item{
    display:flex;
    align-items:center;

    gap:12px;

    padding:14px 20px;

    text-decoration:none;

    color:var(--muted);

    transition:.2s;
}

.penjual-nav-item:hover{
    color:var(--primary);
    background:#FFF1F7;
}

.penjual-nav-item.is-active{
    color:var(--primary);
    background:#FFF1F7;

    border-left:4px solid var(--primary);
}

.penjual-topbar{
    width:100%;
    height:70px;

    background:linear-gradient(
        135deg,
        #D63384,
        #F06292
    );

    color:white;

    display:flex;
    align-items:center;
    justify-content:space-between;

    padding:0 24px;

    box-sizing:border-box;
}

.penjual-content{
    flex:1;
    padding:28px;
}
`