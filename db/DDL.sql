CREATE TABLE editions
(
  name TEXT NOT NULL
);
CREATE TABLE edition_menu
(
  edition_name        TEXT,
  model_number        INTEGER,
  model_name          TEXT,
  short_name          TEXT,
  video_button        BYTEA,
  subscription_button BYTEA,
  image_button        BYTEA
);
CREATE TABLE images_menu
(
  edition_name       TEXT,
  model_number       INTEGER,
  model_name         TEXT,
  short_name         TEXT,
  thumbnail          BYTEA,
  subscription_image BYTEA,
  download_image     BYTEA,
  product_id         INTEGER,
  price_gbp          REAL,
  price_usd          REAL,
  price_eur          REAL
);
CREATE TABLE social_networks
(
  name           TEXT,
  url            TEXT,
  icon_color     TEXT,
  thumbnail_grey BYTEA
);
CREATE TABLE subscriptions_menu
(
  edition_name       TEXT,
  model_number       INTEGER,
  model_name         TEXT,
  short_name         TEXT,
  thumbnail          BYTEA,
  subscription_image BYTEA,
  product_id         TEXT
);
CREATE TABLE videos_menu
(
  edition_name TEXT,
  model_number INTEGER,
  model_name   TEXT,
  short_name   TEXT,
  video_title  TEXT,
  length       INTEGER,
  size         INTEGER,
  price_gbp    REAL,
  price_usd    REAL,
  price_eur    REAL,
  thumbnail    BYTEA,
  video        BYTEA,
  product_id   TEXT
);