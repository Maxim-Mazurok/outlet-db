CREATE TABLE edition_menu
(
  edition_name        TEXT,
  model_number        TEXT,
  model_name          TEXT,
  shoot_name          TEXT,
  video_button        TEXT,
  subscription_button TEXT,
  image_button        TEXT,
  id                  INTEGER DEFAULT nextval('edition_menu_id_seq' :: REGCLASS) PRIMARY KEY NOT NULL
);
CREATE UNIQUE INDEX edition_menu_id_uindex
  ON edition_menu (id);
CREATE TABLE editions
(
  name TEXT                                                               NOT NULL,
  id   INTEGER DEFAULT nextval('editions_id_seq' :: REGCLASS) PRIMARY KEY NOT NULL
);
CREATE UNIQUE INDEX editions_id_uindex
  ON editions (id);
CREATE TABLE images_menu
(
  edition_name   TEXT,
  model_number   TEXT,
  model_name     TEXT,
  shoot_name     TEXT,
  thumbnail      TEXT,
  download_image TEXT,
  product_id     TEXT,
  price_gbp      REAL,
  price_usd      REAL,
  price_eur      REAL,
  id             INTEGER DEFAULT nextval('images_menu_id_seq' :: REGCLASS) PRIMARY KEY NOT NULL
);
CREATE UNIQUE INDEX images_menu_id_uindex
  ON images_menu (id);
CREATE TABLE social_networks
(
  name           TEXT,
  url            TEXT,
  icon_color     TEXT,
  thumbnail_grey TEXT,
  id             INTEGER DEFAULT nextval('social_networks_id_seq' :: REGCLASS) PRIMARY KEY NOT NULL
);
CREATE UNIQUE INDEX social_networks_id_uindex
  ON social_networks (id);
CREATE TABLE subscriptions_menu
(
  edition_name       TEXT,
  model_number       TEXT,
  model_name         TEXT,
  shoot_name         TEXT,
  thumbnail          TEXT,
  subscription_image TEXT,
  product_id         TEXT,
  id                 INTEGER DEFAULT nextval('subscriptions_menu_id_seq' :: REGCLASS) PRIMARY KEY NOT NULL
);
CREATE UNIQUE INDEX subscriptions_menu_id_uindex
  ON subscriptions_menu (id);
CREATE TABLE videos_menu
(
  edition_name TEXT,
  model_number TEXT,
  model_name   TEXT,
  shoot_name   TEXT,
  video_title  TEXT,
  length       INTEGER,
  size         INTEGER,
  price_gbp    REAL,
  price_usd    REAL,
  price_eur    REAL,
  thumbnail    TEXT,
  video        TEXT,
  product_id   TEXT,
  id           INTEGER DEFAULT nextval('videos_menu_id_seq' :: REGCLASS) PRIMARY KEY NOT NULL
);
CREATE UNIQUE INDEX videos_menu_id_uindex
  ON videos_menu (id);