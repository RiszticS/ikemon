# IKémon

![ikemon.png](ikemon.png)

---

## Történet
Pokémonok szabadultak be a világunkba! Az NFT kereskedők gyorsan megragadták az alkalmat, hogy elkészítsenek egy weboldalt a kártyák adás-vételére. Természetesen a programozási feladat rád hárul...

---

## Alap funkciók
- PHP-alapú szerveroldali alkalmazás, ahol felhasználók Pokémon kártyákkal kereskedhetnek.
- Az adatokat két struktúra kezeli: kártyák és felhasználók.
- Előre feltöltött Pokémon kártyák.
- Beépített admin felhasználó a kártyák kezelésére.
- Egy kártya csak egy felhasználóhoz tartozhat, de egy felhasználó több kártyával is rendelkezhet.

---

## Főoldal / Listaoldal
- Cím és ismertetés.
- Mindenki böngészheti.
- Pokémon kártyák listázása.
- Kártyák linkjeikkel a részletező oldalra vezetnek.
- Bejelentkezett felhasználók vásárolhatnak admin kártyákból.
- Link a "Felhasználó részletek" oldalhoz.

---

## Kártya részletek
- Név, kép, tulajdonságok, leírás.
- Háttérszín a Pokémon eleme szerint.
- Navigációs linkek.

---

## Felhasználó részletek
- Felhasználó adatai és kártyái.
- Kártyák eladása az adminnak 90%-os áron.

---

## Hitelesítési oldalak
- Regisztráció: felhasználónév, email, jelszó.
- Helyes email-formátum és egyedi felhasználónév kötelező.
- Sikeres regisztráció után kezdeti pénzjutalom.
- Bejelentkezés: felhasználónév és jelszó.
- Hibajelzések megjelenítése.

---

## Admin funkciók
- Speciális admin felhasználó (felhasználónév: admin, jelszó: admin).
- Kártya létrehozása.
- Admin korlátlan számú kártyát birtokolhat.
- Admin nem vásárolhat kártyát.

---

## További elvárások
- Igényes megjelenés (legalább 1024x768 felbontáson).
- Saját CSS vagy keretrendszer használata engedélyezett.
- Adatbevitel szerveroldali validálása.
- Űrlapoknál `novalidate` attribútum.
- Beadás: teljes projekt README.md-vel.
- Csak PHP, nincs keretrendszer vagy külső PHP könyvtár.

---

## Adattárolás
**Pokémon kártya adatok:**
- Név, HP, elem, támadás, védekezés, ár, leírás, kép.

**Felhasználó adatok:**
- Név, email, jelszó, egyenleg, admin jogosultság.

**Adattárolás lehetőségei:**
- Felhasználóhoz mentett kártya azonosítók.
- Kártyához rendelt felhasználó azonosító.

---

## Tervezési lépések
1. Statikus HTML prototípus készítése.
2. Sükséges adatok meghatározása.
3. Adatok tárolási módjának megtervezése.
4. Adatok eléréséhez segítő függvények készítése.
5. Űrlapok kezelése: siker és hibakezelés.

---

## Kártya tárolási példa (JSON)
```json
{
    "card0": {
        "name": "Pikachu",
        "type": "electric",
        "hp": 60,
        "attack": 20,
        "defense": 20,
        "price": 160,
        "description": "Pikachu that can generate powerful electricity...",
        "image": "https://assets.pokemon.com/assets/cms2/img/pokedex/full/025.png"
    }
}
```

