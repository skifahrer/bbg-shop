CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item VARCHAR(255) NOT NULL,
    oxprice DECIMAL(10, 2) NOT NULL,
    weight DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    length INT NOT NULL,
    width INT NOT NULL,
    height INT NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    title_sk VARCHAR(255) NOT NULL,
    title_sl VARCHAR(255) NOT NULL,
    title_hu VARCHAR(255) NOT NULL,
    title_hr VARCHAR(255) NOT NULL,
    title_ro VARCHAR(255) NOT NULL,
    title_bg VARCHAR(255) NOT NULL,
    features_json JSON NOT NULL
);

INSERT INTO products (item, oxprice, weight, stock, length, width, height, title_en, title_sk, title_sl, title_hu, title_hr, title_ro, title_bg, features_json) VALUES
('10046361', 1, 10.3, 13, 41, 43, 51, 'Happy Hour 38 mini fridge minibar 38 litres 26 dB', 'Happy Hour 38, mini chladnička, minibar, 38 litrov, 26 dB', 'Happy Hour 38, mini chladnička, minibar, 38 litrov, 26 dB', 'Happy Hour 38, mini hűtőszekrény, minibár, 38 liter, 26 dB', 'Happy Hour 38, mini hladnjak, mini bar, 38 litara, 26 dB', 'Happy Hour 38, mini-frigider, minibar, frigider pentru băuturi, 38 litri, 26 dB', 'Happy Hour 38, мини хладилник, минибар, охладител за напитки, 38 литра, 26 dB', '{"features": [{"desc": "<b>Kompakt:</b> freistehender Mini-Kühlschrank ideal für Singles, Büros, Hotel- oder Gästezimmer", "name": null, "image": null}, {"desc": "<b>Platzwunder:</b> 38-Liter-Innenraum für Getränke und Lebensmittel", "name": null, "image": null}, {"desc": "<b>Kühle Getränke:</b> 2-Liter-Flaschenfach in der Tür", "name": null, "image": null}, {"desc": "<b>3 Lagerebenen:</b> 2 herausziehbare Gitterebenen mit 3 Einschüben", "name": null, "image": null}, {"desc": "<b>Still & Leise:</b> besonders geräuscharmer Betrieb mit nur 26 dB", "name": null, "image": null}, {"desc": "<b>Einfach bedient:</b> mechanische Kühltemperatureinstellung", "name": null, "image": null}]}'),
('10046360', 1, 10.3, 26, 41, 43, 51, 'Happy Hour 38 mini fridge minibar beverage fridge 38 L 26 dB', 'Happy Hour 38, mini chladnička, minibar, chladnička na nápoje, 38 l, 26 dB', 'Happy Hour 38, mini hladilnik, minibar, hladilnik za pijačo, 38 litrov, 26 dB', 'Happy Hour 38, mini hűtőszekrény, minibár, italhűtő, 38 l, 26 dB', 'Happy Hour 38, mini hladnjak, minibar, hladnjak za piće, 38 l, 26 dB', 'Happy Hour 38, mini frigider, minibar, frigide rpentru băuturi, 38 l, 26 dB', 'Happy Hour 38, мини хладилник, минибар, охладител за напитки, 38 литра, 26 dB', '{"features": [{"desc": "<b>Kompakt:</b> freistehender Mini-Kühlschrank ideal für Singles, Büros, Hotel- oder Gästezimmer", "name": null, "image": null}, {"desc": "<b>Platzwunder:</b> 38-Liter-Innenraum für Getränke und Lebensmittel", "name": null, "image": null}, {"desc": "<b>Kühle Getränke:</b> 2-Liter-Flaschenfach in der Tür", "name": null, "image": null}, {"desc": "<b>3 Lagerebenen:</b> 2 herausziehbare Gitterebenen mit 3 Einschüben", "name": null, "image": null}, {"desc": "<b>Still & Leise:</b> besonders geräuscharmer Betrieb mit nur 26 dB", "name": null, "image": null}, {"desc": "<b>Einfach bedient:</b> mechanische Kühltemperatureinstellung", "name": null, "image": null}]}'),
('10046359', 1, 10.3, 43, 41, 43, 51, 'Happy Hour 38 mini fridge minibar beverage fridge 38 L 26 dB', 'Happy Hour 38, mini chladnička, minibar, chladnička na nápoje, 38 l, 26 dB', 'Happy Hour 38, mini hladilnik, minibar, hladilnik za pijačo, 38 litrov, 26 dB', 'Happy Hour 38, mini hűtőszekrény, minibár, italhűtő, 38 l, 26 dB', 'Happy Hour 38, mini hladnjak, minibar, hladnjak za piće, 38 l, 26 dB', 'Happy Hour 38, mini frigider, minibar, frigide rpentru băuturi, 38 l, 26 dB', 'Happy Hour 38, мини хладилник, минибар, охладител за напитки, 38 литра, 26 dB', '{"features": [{"desc": "<b>Kompakt:</b> freistehender Mini-Kühlschrank ideal für Singles, Büros, Hotel- oder Gästezimmer", "name": null, "image": null}, {"desc": "<b>Platzwunder:</b> 38-Liter-Innenraum für Getränke und Lebensmittel", "name": null, "image": null}, {"desc": "<b>Kühle Getränke:</b> 2-Liter-Flaschenfach in der Tür", "name": null, "image": null}, {"desc": "<b>3 Lagerebenen:</b> 2 herausziehbare Gitterebenen mit 3 Einschüben", "name": null, "image": null}, {"desc": "<b>Still & Leise:</b> besonders geräuscharmer Betrieb mit nur 26 dB", "name": null, "image": null}, {"desc": "<b>Einfach bedient:</b> mechanische Kühltemperatureinstellung", "name": null, "image": null}]}');
