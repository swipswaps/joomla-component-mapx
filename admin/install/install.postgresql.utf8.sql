CREATE TABLE "#__xmap_sitemap" (
  "id"             SERIAL                                                    NOT NULL,
  "title"          CHARACTER VARYING(255) DEFAULT NULL,
  "alias"          CHARACTER VARYING(255) DEFAULT NULL,
  "introtext"      TEXT                   DEFAULT NULL,
  "params"         TEXT                   DEFAULT NULL,
  "selections"     TEXT                   DEFAULT NULL,
  "excluded_items" TEXT                   DEFAULT NULL,
  "is_default"     INTEGER                DEFAULT 0,
  "publish"        INTEGER                DEFAULT NULL,
  "access"         INTEGER                DEFAULT NULL,
  "created"        TIMESTAMP WITHOUT TIME ZONE DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "count_xml"      INTEGER                DEFAULT NULL,
  "count_html"     INTEGER                DEFAULT NULL,
  "views_xml"      INTEGER                DEFAULT NULL,
  "views_html"     INTEGER                DEFAULT NULL,
  "lastvisit_xml"  INTEGER                DEFAULT NULL,
  "lastvisit_html" INTEGER                DEFAULT NULL,
  PRIMARY KEY ("id")
);

CREATE TABLE "#__xmap_items" (
  "uid"        CHARACTER VARYING(100) NOT NULL,
  "itemid"     INTEGER                NOT NULL,
  "view"       CHARACTER VARYING(10)  NOT NULL,
  "sitemap_id" INTEGER                NOT NULL,
  "properties" VARCHAR(300) DEFAULT NULL,
  PRIMARY KEY ("uid", "itemid", "view", "sitemap_id")
);

CREATE INDEX "#__xmap_items_idx_uid" ON "#__xmap_items" ("uid", "itemid");
CREATE INDEX "#__xmap_items_idx_view" ON "#__xmap_items" ("view");
