# Admin használati útmutató – lépésről lépésre

Ez az útmutató ahhoz készült, hogy az admin felületen **rendszerben** tudd feltölteni a portfóliót: kategóriák, címkék, képek, oldalak, szövegek, blog.

**Admin URL:** `http://portfolio.loc/admin`  
(vagy a saját domained `/admin` címe)

A publikus oldal megnyitása: az admin menü jobb oldalán a **Public site** link, vagy pl. `http://portfolio.loc/hu`.

> Jelenleg az admin **nincs jelszóval védve** (belépés később jön). Éles környezetben ne hagyd nyitva.

---

## 1. Mitől működik a főoldal? (rövid áttekintés)

A publikus oldal tartalma ezekből áll össze:


| Mi                                 | Hol állítod                        | Hol jelenik meg          |
| ---------------------------------- | ---------------------------------- | ------------------------ |
| Menüpontok, hero szöveg, SEO       | **Pages**                          | Fejléc menü, hero sáv    |
| Kezdőlap szekciók (szöveg + képek) | **Page blocks** + kapcsolódó fotók | Kezdőlap                 |
| Galéria / panoráma képek           | **Photos**                         | `/galeria`, `/panoramak` |
| Blog bejegyzések                   | **Blog**                           | `/blog`                  |
| Kapcsolat űrlap üzenetek           | **Messages**                       | csak adminban            |
| Oldal neve, egyéb kulcsok          | **Settings**                       | fejléc / lábléc          |


**Ajánlott feltöltési sorrend** (alulról építkezünk):

1. Beállítások (Settings)
2. Fotó kategóriák
3. Címkék (Tags)
4. Fotók feltöltése
5. Oldalak (Pages) szövegei / hero
6. Oldalblokkok (Page blocks) és a hozzájuk kötött képek
7. Blog (opcionális)
8. Ellenőrzés a publikus oldalon

Ha a seed már lefutott, sok adat megvan – akkor elég a meglévőt szerkeszteni vagy új fotókat hozzáadni.

---



## 2. Belépés és tájékozódás

1. Nyisd meg: `/admin`
2. A **Dashboard** mutatja, hány oldal, fotó, blog, üzenet van.
3. Felső menü:
  - **Content** → Pages, Page blocks, Block items, Blog  
  - **Photos** → Photos, Photo categories, Tags  
  - **Messages** → kapcsolat űrlap beküldései  
  - **Settings** → oldalbeállítások  
  - **Public site** → publikus nézet új lapon

**Tipikus lista műveletek**

- **Új** gomb: új rekord  
- Soron **dupla kattintás**: szerkesztés  
- **View / Edit / Delete** ikonok a sor végén  
- Kereső: Ctrl+K (ha van keresőmező)

---



## 3. Beállítások (Settings)

**Menü:** Settings  

1. Nyisd meg a listát.
2. Szerkeszd legalább a `site_name` értéket (ez jelenik meg az oldalon névként).
3. Mentsd.

Később ide kerülhetnek további kulcs–érték párok (pl. kapcsolat email). Új beállításnál add meg a **key** (programozott azonosító), **label** és **value** mezőket.

---



## 4. Fotó kategóriák

**Menü:** Photos → Photo categories  

Ezek nélkül nem tudsz fotót menteni (kötelező kategória).

A tipikus kategóriák (seed alapján):


| Név       | Slug        | Szerep                            |
| --------- | ----------- | --------------------------------- |
| portré    | `portre`    | galéria                           |
| természet | `termeszet` | galéria                           |
| város     | `varos`     | galéria                           |
| panoráma  | `panorama`  | panoráma oldal                    |
| háttér    | `hatter`    | hero / banner képek (nem galéria) |


**Új kategória:**

1. Add new
2. Név, slug (pl. `eskuvo`), Visible = igen, Pos = sorrend
3. Mentés

A **slug**-ot később ne változtasd meg könnyelműen – a kód a `panorama` slug alapján ismeri fel a panorámákat.

---



## 5. Címkék (Tags)

**Menü:** Photos → Tags  

A galériában / panorámáknál szűrőként jelennek meg.

1. Hozz létre címkéket (pl. `portré`, `utca`, `éjszaka`).
2. Visible = igen.
3. A fotó szerkesztésénél majd multi-selecttel hozzárendeled őket.

---



## 6. Fotók feltöltése (legfontosabb)

**Menü:** Photos → Photos → Add photo  

### 6.1 Új kép hozzáadása

1. **Image file** – válaszd ki a fájlt (JPG / PNG / WEBP / GIF).
2. **Category** – kötelező (galériához ne `hatter`, panorámához `panorama`).
3. **Title** – megjelenő cím.
4. **Original file name** – általában **automatikusan** kitöltődik a feltöltött fájl nevéből (kiterjesztés nélkül). Ez jelenik meg a képnézőben „ID”-ként (nem az UUID).
5. **Slug** – üresen hagyható; a címből generálódik.
6. **City / Location** – város és helyszín (szűréshez / képnézőhöz).
7. **Description** – hosszabb leírás.
8. **Tags** – válassz egy vagy több címkét (Tom Select).
9. **In gallery** – ha a galériában is látszódjon.
  - Panorámáknál tipikusan **kikapcsolva**, a kategória `panorama`.
10. **Visible** – legyen látható a publikus oldalon.
11. **Position (pos)** – kisebb szám = előrébb.
12. **Save**.



### 6.2 Mit csinál a rendszer helyetted?

- A fájlt ide menti: `webroot/img/uploads/ÉV/HÓNAP/{id}.kiterjesztés`  
- Az **EXIF** adatokat (gép, objektív, záridő, rekesz, ISO, fókusz, dátum, felbontás) **kiolvassa a fájlból** és elmenti – ezeket az űrlapon nem kell kézzel kitölteni.  
- Törléskor a rekord **és** a fájl is törlődik.



### 6.3 Meglévő kép cseréje

1. Photos → Edit
2. **Replace image** – új fájl
3. Mentés → a régi fájl törlődik, az EXIF és az `original_name` frissül



### 6.4 Hol jelenik meg melyik kép?


| Cél                      | Beállítás                                                              |
| ------------------------ | ---------------------------------------------------------------------- |
| Galéria (`/galeria`)     | Visible + In gallery + nem panoráma kategória                          |
| Panorámák (`/panoramak`) | Visible + kategória slug = `panorama`                                  |
| Kezdőlap blokk / hero    | A fotót később **Page block**-hoz vagy **Page → Hero photo**-hoz kötöd |
| Háttér / banner          | Kategória `hatter`, In gallery általában ki                            |




### 6.5 Lista tippek

- Az indexen látszik az előnézet, cím, város, EXIF összefoglaló.  
- Dupla kattintás a soron = szerkesztés.

---



## 7. Oldalak (Pages) — nyelvek

**Menü:** Content → Pages  

A szöveges mezők (menüfelirat, cím, hero, meta, body) az űrlapon **Magyar / English / Deutsch** füleken szerkeszthetők.  
Slug, URL, hero kép, sablon, láthatóság – nyelvfüggetlen.

Ugyanígy nyelvi TAB van: Page blocks, Block items, Photos, Tags, Photo categories, Blog, Settings.

A publikus oldalon csak **hu / en / de** nyelvváltó jelenik meg (olasz és francia kikapcsolva).


Fontos slugok (ne töröld / ne nevezd át, ha a sablon ezekre épül):


| Slug        | Oldal     |
| ----------- | --------- |
| `home`      | Kezdőlap  |
| `galeria`   | Galéria   |
| `panoramak` | Panorámák |
| `blog`      | Blog      |
| `rolam`     | Rólam     |
| `kapcsolat` | Kapcsolat |




### 7.1 Oldal szerkesztése

1. Pages → a kívánt sor → Edit (vagy dupla katt).
2. **Datasheet** fül:
  - **Menu label** – menüben megjelenő szöveg  
  - **Title** – oldal címe  
  - **Hero title / Hero lead** – hero nagy cím és alcím  
  - **Hero photo** – háttérkép (válaszd a feltöltött fotók közül)  
  - **Url** – pl. `/` vagy `/galeria`  
  - **Visible / Pos** – megjelenés és menüsorrend
3. **Meta Description** – SEO rövid leírás.
4. **Body** – ha az oldal sablonja használ törzsszöveget.
5. Mentés.

> A **kezdőlap** tartalmi szekciói nem csak a Page mezőkből jönnek, hanem a **Page blocks** listából (lásd következő fejezet).



### 7.2 Nyelvek

A publikus oldal több nyelvű (`/hu`, `/en`, `/de`, …).  
A menü- és oldalszövegek fordításai az `i18n` táblában / Translate viselkedéssel élnek.  
Ha egy nyelven üres a fordítás, a magyar (alap) szöveg jelenhet meg.  
Új nyelvű szövegekhez a seed / fordítási folyamatot használd, vagy az adott mező locale szerinti szerkesztését (ha az adminban megjelenik).

---



## 8. Oldalblokkok (Page blocks) – kezdőlap szekciók

**Menü:** Content → Page blocks  

Egy **page block** = egy szekció egy oldalon (pl. kezdőlap „portrék” blokk).

### 8.1 Blokk típusok (`block_type`)

A sablonok a `templates/element/blocks/` alatt vannak. Használd ezeket a neveket:


| block_type    | Mit csinál                                      |
| ------------- | ----------------------------------------------- |
| `photos_text` | Képek + szöveg (kezdőlap galéria-szerű szekció) |
| `banner`      | Banner / köztes kép                             |
| `cta`         | Felhívás (call to action)                       |
| `text_photo`  | Szöveg + egy kiemelt kép                        |
| `intro`       | Bevezető szöveg (pl. panoráma oldal)            |
| `contact`     | Kapcsolat szekció                               |




### 8.2 Új blokk létrehozása

1. Page blocks → Add
2. **Page** – melyik oldalhoz tartozik (pl. home)
3. **Block type** – pl. `photos_text`
4. **Title / Body / Quote** – a szekció szövegei
5. **Featured photo** – ha a típus egy kiemelt képet vár (banner, text_photo, cta)
6. **Layout** – ha van (opcionális elrendezés)
7. **Visible / Pos** – sorrend a lapon belül
8. Mentés



### 8.3 Képek hozzárendelése a blokkhoz

A `photos_text` típusú blokkoknál a képeket a kapcsolótáblán keresztül kötöd:

**Menü:** Content → (Page blocks → View a blokkra) **vagy** Page blocks photos  

1. Nyisd meg a blokkot (View).
2. A kapcsolódó fotóknál Add / szerkesztés:
  - válaszd ki a **Photo**-t  
  - **CSS class** pl. `photo-tall`, `photo-mid` (méret a layoutban)  
  - **Pos** – sorrend
3. Mentés

Így jelennek meg a kezdőlapon a szekció képei.

### 8.4 Block items

**Menü:** Content → Block items  

Egyes blokktípusokhoz tartozó listaelemek (ha a sablon használja).  
Csak akkor kell, ha a blokkhoz tételes lista tartozik – a sima szöveges / képes blokkokhoz gyakran elég a block + fotók.

---



## 9. Blog

**Menü:** Content → Blog  

1. Add – cím, slug, lead, body, dátum, Visible.
2. Mentés.
3. Ha a bejegyzéshez képek kellenek: a blog–fotó kapcsolaton keresztül add hozzá a már feltöltött Photos rekordokat (Blog posts photos / a bejegyzés View oldaláról).

A publikus `/blog` oldalon a látható bejegyzések jelennek meg; a képekre kattintva a lightbox nyílik.

---



## 10. Kapcsolat üzenetek

**Menü:** Messages  

A publikus kapcsolat űrlapról beérkező üzenetek listája.

1. View – elolvasás
2. Szükség szerint törlés
3. Új üzenetet általában **nem** az adminból kell felvenni (az űrlap a publikus oldalon van)

---



## 11. Teljes „nulláról feltöltöm” forgatókönyv

Ha üres / majdnem üres az adatbázis, kövesd ezt:

### A. Alap

1. `/admin` → Settings → `site_name`
2. Photo categories – legalább: portre, termeszet, varos, panorama, hatter
3. Tags – néhány szűrőcímke



### B. Képek

1. Tölts fel **háttér / hero** képeket (`hatter`, In gallery ki).
2. Tölts fel **galéria** képeket (megfelelő kategória, In gallery be, Visible be, címkék).
3. Tölts fel **panoráma** képeket (kategória `panorama`).



### C. Oldalak

1. Pages – ellenőrizd / szerkeszd: home, galeria, panoramak, blog, rolam, kapcsolat
  - menüfelirat, hero szöveg, hero kép



### D. Kezdőlap tartalom

1. Page blocks a `home` oldalhoz: photos_text / banner / cta sorrendben
2. Minden photos_text blokkhoz köss fotókat (Page blocks photos)
3. A `rolam` / `kapcsolat` oldalakhoz text_photo, contact, intro blokkok ahogy kell



### E. Blog (opcionális)

1. 1–2 blogposzt + képek



### F. Ellenőrzés

1. Public site → `/hu`
2. Nézd meg: menü, kezdőlap szekciók, galéria szűrés, panoráma, blog, kapcsolat űrlap
3. Nyiss meg egy képet – az **ID** mezőben az `original_name` (fájlnév/sorszám) jelenjen meg

---



## 12. Gyakori hibák


| Probléma                        | Megoldás                                                                                  |
| ------------------------------- | ----------------------------------------------------------------------------------------- |
| A fotó nem látszik a galériában | Visible + In gallery legyen be; kategória ne `panorama` / ne csak `hatter`                |
| Panoráma nem jelenik meg        | Kategória slug pontosan `panorama`, Visible be                                            |
| Kezdőlap üres szekció           | Van-e Page block a `home` oldalhoz? Hozzá vannak-e rendelve fotók?                        |
| Hero nincs / rossz kép          | Pages → Hero photo mező                                                                   |
| EXIF üres                       | A fájlban nincs EXIF (pl. erősen tömörített PNG/WEBP), vagy a kép cseréje után ments újra |
| Törlés után megmaradt a fájl?   | A rendszer törli az `uploads/...` fájlt; frissítsd a mappát                               |
| Menü rossz nyelven              | Fordítások / locale – a magyar mezőt szerkeszd először, majd a többi locale-t             |


---



## 13. Gyors ellenőrzőlista (minden új tartalomnál)

- [ ] Kategória és címke megvan  
- [ ] Fotó feltöltve, Visible megfelelő  
- [ ] Galéria / panoráma flag + kategória stimmel  
- [ ] Ha kezdőlapra kell: Page block + fotó kapcsolat  
- [ ] Ha hero/banner: Page vagy block featured/hero photo beállítva  
- [ ] Publikus oldalon ránéztem (`/hu` …)  

---



## 14. Hol vannak a fájlok a lemezen?

- Feltöltött képek: `webroot/img/uploads/ÉV/HÓNAP/{id}.ext`  
- Védelmi pajzs (generált): `webroot/protect/{slug}.png`  
- Statikus seed / régi képek: `webroot/img/` (ha van)

Az adatbázisban a `photos.filename` pl. `uploads/2026/09/12.jpg`, az `original_name` pl. `IMG_4821`.

---

*Dokumentum a MyPortfolio projekthez. Frissítsd, ha az admin menü vagy a blokktípusok változnak.*