Berikut versi teksnya yang bisa langsung kamu copy sebagai `design.md`:

````
# Design.md — Eco Real Estate Landing Page

## Overview

Buat landing page real-estate bertema eco-friendly / nature-inspired yang secara visual mengikuti referensi gambar: rounded container, warna hijau natural, foto rumah di tengah alam, typography modern, dan layout premium/minimal.

Target utama: desktop-first, tetapi tetap responsive untuk tablet/mobile.

## Visual Direction

- Style: modern eco-luxury, clean, warm, natural.
- Background utama: `#F5F4EC`.
- Primary green: `#2F6B2F`.
- Dark green: `#183D22`.
- Light green: `#DCE9BE`.
- Text: `#25351F`.
- Muted text: `#7D8575`.
- Card: putih/off-white dengan shadow lembut.
- Border radius: besar, sekitar `24–32px`.
- Button radius: pill `999px`.
- Shadow: lembut dan diffuse.
- Image: natural daylight, warm sunlight, banyak greenery, rumah kayu modern.

## Typography

Gunakan font sans-serif modern seperti Inter, Manrope, atau Plus Jakarta Sans.

- H1: 52–64px, weight 700–800.
- H2: 32–40px, weight 700.
- H3: 18–20px, weight 700.
- Body: 14–16px, line-height 1.6.
- Navigation: 13–14px, medium.

# 1. Global Shell

Gunakan background `#F5F4EC`.

Content utama memiliki max-width sekitar `1180–1280px` dan berada di tengah.

Gunakan whitespace yang lega agar desain terasa premium dan tidak padat.

# 2. Navbar

## Sebelum Scroll

Navbar berada di atas hero dalam bentuk floating rounded capsule.

- Width: `calc(100% - 48px)`.
- Max-width: `1180–1280px`.
- Margin top: `24–38px`.
- Height: `64–72px`.
- Background: `rgba(255,255,255,.92)`.
- Backdrop blur: `12–18px`.
- Border radius: `999px`.
- Shadow subtle.
- Position: absolute/floating.
- Z-index tinggi.

### Isi Navbar

Kiri:
- Logo: `Pouils`.
- Warna logo: primary green.
- Font weight: 800.

Tengah:
- Home
- Find
- Real Estate
- Couch / Contact

Kanan:
- Search icon.
- CTA hijau: `Get In Touch`.

## Setelah Scroll

Ketika user melakukan scroll ke bawah:

1. Navbar berubah menjadi `position: fixed`.
2. Navbar menyatu ke sisi kiri dan kanan viewport.
3. Width menjadi `100%`.
4. Top menjadi `0`.
5. Border radius menjadi `0`.
6. Height sekitar `64px`.
7. Background menjadi `rgba(255,255,255,.97)`.
8. Tambahkan subtle bottom shadow/border.
9. Transition smooth sekitar `300ms ease`.
10. Isi navbar tetap menggunakan max-width agar tidak menempel ke tepi layar.

Contoh:

Sebelum scroll:

```text
┌──────────────────────────────────────────────┐
│  Logo       Home  Find  Real Estate    CTA  │
└──────────────────────────────────────────────┘
          floating rounded capsule
````

 Setelah scroll:

```
┌──────────────────────────────────────────────┐
│  Logo       Home  Find  Real Estate    CTA  │
└──────────────────────────────────────────────┘
          full viewport width
```

 Navbar harus terasa seperti berubah dari floating capsule menjadi navbar website biasa yang menempel penuh ke bagian atas layar.

 ## Responsive Navbar

 Pada mobile:

 - Logo tetap terlihat.
- Menu desktop disembunyikan.
- Tampilkan hamburger button.
- Navbar tetap floating saat berada di posisi paling atas.
- Saat scroll, navbar berubah menjadi full-width fixed bar.
- Radius menjadi `0`.

 # 3\. Hero Section

 Hero menjadi visual paling dominan.

 - Desktop height: `520–620px`.
- Background image full bleed.
- Border radius bawah sekitar `28px`.
- Foto rumah modern/kayu di tengah padang rumput dan pepohonan.
- Background berupa hutan atau pegunungan.
- Gunakan warm sunrise/sunset.
- Tambahkan overlay gradient tipis di sisi kiri agar text mudah dibaca.

 ## Hero Content

 Posisi kiri bawah / kiri tengah.

 Badge:

 `⌂ Premium Homes`

 Heading:

```
Find Eco-Friendly
Homes Easily
```

 Paragraph pendek sekitar 2 baris.

 CTA:

 `Explore Homes →`

 CTA menggunakan green pill.

 Rumah harus menjadi focal point dan overlay jangan terlalu gelap.

 # 4\. Floating Search Bar

 Di bawah hero terdapat search/filter bar yang overlap dengan hero.

 - Width: `75–85%`.
- Max-width: sekitar `900px`.
- Background: white.
- Border radius: `999px`.
- Shadow medium-soft.
- Padding: `10–12px`.
- Display flex.

 Field:

 - Location.
- Property Type.
- Rooms.
- Search icon.
- CTA `Search`.

 Pada mobile search bar berubah menjadi layout vertical/card.

 # 5\. Welcome Section

 Background off-white.

 Gunakan layout 2 kolom pada desktop.

 ## Left

 Foto rumah eco-friendly dalam frame/card.

 - Aspect ratio sekitar 4:3.
- Border radius `18–24px`.
- Circular green badge di pojok kiri atas.
- Circular image/leaf badge kecil di kanan bawah.

 ## Right

 Eyebrow kecil.

 Heading:

```
Welcome to Treopps?
```

 Body text 2–3 baris.

 CTA:

 `Explore →`

 Tambahkan secondary link kecil di sebelah CTA.

 # 6\. Featured Homes

 Background sedikit lebih hijau/cream, misalnya:

 `#EEF4D9`

 Heading center:

```
10 Eco-Friendly Homes
```

 Subheading opsional.

 ## Property Cards

 Desktop menggunakan 3 card per row.

 Setiap card:

 - Background white/off-white.
- Border radius `18–22px`.
- Overflow hidden.
- Shadow subtle.
- Image ratio sekitar `16:10`.
- Padding content `18–20px`.

 ### Card 1

 Title:

 `Well Eco-Friendly Homes`

 Description pendek.

 Button:

 `Learn More`

 ### Card 2

 Title:

 `Sustainably Living Homes`

 Description pendek.

 Button:

 `View Home`

 ### Card 3

 Title:

 `Comfortable Eco Living`

 Description pendek.

 Button:

 `View Home`

 Button menggunakan green pill.

 Di bawah cards terdapat pagination dots:

```
● ○ ○ ○
```

 Active dot berwarna primary green.

 # 7\. CTA Section

 Tambahkan CTA section setelah featured homes.

 Background dark green.

 Heading putih:

```
Find a home that feels good for you and the planet.
```

 Gunakan CTA putih atau light-green.

 Section menggunakan rounded container besar dengan padding `48–64px`.

 # 8\. Footer

 Footer sederhana.

 Isi:

 - Logo.
- Short description.
- Navigation links.
- Social links.
- Copyright.

 Background dark green.

 Text putih dengan muted opacity.

 # Interaction & Motion

 Gunakan animasi yang subtle dan premium.

 ## Navbar

 Gunakan transition:

```
.navbar {
  transition:
    width 300ms ease,
    top 300ms ease,
    border-radius 300ms ease,
    background 300ms ease,
    box-shadow 300ms ease;
}

.navbar.scrolled {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  border-radius: 0;
  background: rgba(255,255,255,.97);
  box-shadow: 0 4px 20px rgba(20,50,25,.08);
}
```

 Gunakan class `.scrolled` berdasarkan:

```
window.scrollY > 40
```

 Jangan mengubah ukuran logo/menu secara drastis ketika scroll agar layout tidak terasa melompat.

 ## Property Cards

 Saat hover:

 - Card naik sekitar `4px`.
- Image melakukan zoom `scale(1.03)`.
- Transition `250–350ms ease`.

 ## Buttons

 Saat hover:

 - Warna sedikit lebih gelap.

 Saat active:

 - Sedikit scale down.

 # Responsive Rules

 ## Desktop ≥ 1024px

 - Hero besar.
- Navbar horizontal.
- Intro 2 kolom.
- Featured homes 3 kolom.
- Search bar horizontal.

 ## Tablet 768–1023px

 - Hero sedikit lebih pendek.
- Featured cards 2 kolom.
- Intro tetap 2 kolom jika cukup.
- Search bar dapat menjadi 2 baris.

 ## Mobile \< 768px

 - Navbar compact.
- Hero height sekitar `560–650px`.
- H1 `38–46px`.
- Search bar vertical.
- Intro 1 kolom.
- Cards 1 kolom.
- Horizontal padding `18–20px`.
- CTA minimal sekitar `44px` tinggi.

 # Image Direction

 Gunakan foto realistis dengan karakteristik:

 - Modern wooden eco home.
- Forest / mountain background.
- Green lawn.
- Warm golden-hour sunlight.
- Natural architecture.
- Premium real-estate photography.
- Tidak ada manusia sebagai focal point.
- Warm earthy colors.

 Hero image harus paling cinematic dan wide.

 Card images harus lebih terang dan jelas supaya properti mudah dilihat.

 # Accessibility

 - Semua image memiliki meaningful `alt`.
- Pastikan contrast text memenuhi WCAG AA.
- Button memiliki focus state.
- Keyboard navigation harus bekerja.
- Mobile menu memiliki `aria-label`.
- Jangan hanya mengandalkan warna untuk status.

 # Component Structure

 Gunakan struktur:

```
App
├── Navbar
├── Hero
│   ├── HeroContent
│   └── SearchBar
├── WelcomeSection
├── FeaturedHomes
│   └── PropertyCard[]
├── CTASection
└── Footer
```

 State:

```
isScrolled
isMobileMenuOpen
activePropertySlide
```

 Navbar scroll logic:

```
useEffect(() => {
  const onScroll = () => setIsScrolled(window.scrollY > 40)

  window.addEventListener('scroll', onScroll)

  return () => window.removeEventListener('scroll', onScroll)
}, [])
```

 Ketika `isScrolled === true`, navbar harus berubah dari floating capsule menjadi full-width fixed bar yang menyatu dengan sisi kiri dan kanan viewport.

 # Design Goal

 Hasil akhir harus terasa seperti website real-estate modern yang premium, tenang, natural, dan trustworthy.

 Fokus utama:

 - Rounded UI.
- Foto rumah di alam.
- Green/off-white palette.
- Floating search bar.
- Property cards.
- Spacing yang lega.
- Typography modern.
- Navbar yang berubah menjadi full-width saat scroll.

 Prioritaskan kesamaan komposisi, hierarchy, spacing, warna, dan behavior navbar dengan referensi daripada menyalin teks atau detail visual secara literal.

```

```