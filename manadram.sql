PRAGMA encoding = 'UTF-8';
PRAGMA page_size = 8192;  -- blob optimisation https://www.sqlite.org/intern-v-extern-blob.html
PRAGMA foreign_keys = ON;
-- The VACUUM command may change the ROWIDs of entries in any tables that do not have an explicit INTEGER PRIMARY KEY

DROP TABLE IF EXISTS play;
DROP TABLE IF EXISTS stats;
DROP TABLE IF EXISTS pattern;
DROP TABLE IF EXISTS configuration;
DROP TABLE IF EXISTS role;

CREATE TABLE play (
  -- une pièce
  id         INTEGER, -- rowid auto
  code       TEXT,    -- nom de fichier sans extension, unique pour la base
  author     TEXT,    -- auteur
  title      TEXT,    -- titre
  genre      TEXT,    -- nom de genre
  created    INTEGER, -- année de création
  lustrum    TEXT,    -- lustre de création
  html		TEXT,	-- html du tableau d'occupation scénique
  filemtime  INTEGER, -- date de dernière modification du fichier pour update
  publisher  TEXT,    -- URL de la source XML
  identifier TEXT,    -- URL du site de référence
  source     TEXT,    -- URL du TEI
  date       INTEGER, -- année pertinente
  issued     INTEGER, -- année de publication
  roles      INTEGER, -- nombre de personnages en tout
  speakers   INTEGER, -- nombre de personnages parlants
  croles     INTEGER, -- presence totale de tous les personnage en nombre de signes
  cspeakers  INTEGER, -- présence totale des personnages parlants (Σ configuration(c*speakers))
  acts       INTEGER, -- nombre d’actes, essentiellement 5, 3, 1 ; ajuster pour les prologues
  scenes     INTEGER, -- nombre de scènes
  confs      INTEGER, -- nombre de scènes
  verse      BOOLEAN, -- uniquement si majoritairement en vers, ne pas cocher si chanson mêlée à de la prose
  type       TEXT,    -- comedy|tragedy (terme normalisé)
  c          INTEGER, -- <c> (char) taille en caractères
  w          INTEGER, -- <w> (word) taille en mots
  l          INTEGER, -- <l> taille en vers
  sp         INTEGER, -- <sp> taille en répliques

  PRIMARY KEY(id ASC)
);
CREATE UNIQUE INDEX play_id ON play(id);
CREATE INDEX created ON play(created);
CREATE INDEX code ON play(code);
CREATE INDEX author ON play(author);
CREATE INDEX genre ON play(genre);

CREATE TABLE stats(
	id		INTEGER,
	play_id	INTEGER REFERENCES play(id),
	l		INTEGER,	-- taille des motifs
	c       INTEGER, -- 1 ou 0, le motif prend ou non en compte les confidents
	g       INTEGER, -- 1 ou 0, le motif regroupe ou non les personnages
	value	INTEGER,	-- nombre de motifs
	PRIMARY KEY(id ASC)
);
CREATE INDEX stats_play_id_l_c_g ON stats(play_id, l, c, g);

CREATE TABLE pattern (
  id         INTEGER,
  play_id    INTEGER REFERENCES play(id),    -- PRENDRE L'ID NUMERIQUE (REFERENCES play(id))
  code	TEXT,
  act_n      INTEGER, -- numéro de l'acte
  scene_n    INTEGER, -- numéro de la première scène du motif (PB SI JE PRENDS LES CONFIGURATIONS)
  scene_id	 TEXT,
  int_dec    INTEGER, -- motif en décimal (PB DU FLOAT)
  int_bin    TEXT,    -- motif en binaire
  str_code   TEXT,    -- motif codifié A/AB
  str_id     TEXT,    -- motif avec les id des personnages
  str_name   TEXT,    -- motif avec les noms des personnages
  occurrences	TEXT,	--toutes les occurrences du motif dans la pièce
  l          INTEGER, -- longueur du motif en configurations
  c          INTEGER, -- 1 ou 0, le motif prend ou non en compte les confidents
  g          INTEGER, -- 1 ou 0, le motif regroupe ou non les personnages

  PRIMARY KEY(id ASC)
);
CREATE INDEX pattern_str_code_l_c_g ON pattern(str_code,l,c,g);

CREATE TABLE configuration (
  -- une configuration est un état de la scène (personnages présents)
  id       INTEGER, -- rowid auto
  play     INTEGER REFERENCES play(id),-- id pièce
  act      INTEGER REFERENCES act(id), -- id acte
  scene    INTEGER REFERENCES scene(id), -- rowid scène
  code     TEXT,    -- code de conf (= @xml:id)
  n        INTEGER, -- numéro d’ordre dans la pièce
  label    TEXT,    -- liste de codes de personnage
  roles    INTEGER, -- nombre de rôles présents
  speakers INTEGER, -- nombre de rôles parlants
  spn      INTEGER, -- numéro de répliques
  sp       INTEGER, -- <sp> taille en répliques
  ln       INTEGER, -- numéro du premier vers
  l        INTEGER, -- <l> taille en vers
  wn       INTEGER, -- numéro du premier mot
  w        INTEGER, -- <w> (word) taille en mots
  cn       INTEGER, -- numéro du premier caractère
  c        INTEGER, -- <c> (char) taille en caractères

  PRIMARY KEY(id ASC)
);
CREATE UNIQUE INDEX configuration_code ON configuration(play, code);
CREATE INDEX configuration_act ON configuration(act);

CREATE TABLE role (
  -- un rôle
  id        INTEGER,  -- rowid auto
  play      INTEGER REFERENCES play(id), -- rowid de pièce
  ord       INTEGER,  -- ordre dans la distribution
  code      TEXT,     -- code personne
  label     TEXT,     -- nom affichable
  title     TEXT,     -- description du rôle (mère de…, amant de…) tel que dans la source
  note      TEXT,     -- possibilité de description plus étendue
  rend      TEXT,     -- série de mots clés séparés d’espaces (male|female)? (cadet)
  sex       INTEGER,  -- 1: homme, 2: femme, null: ?, 0: asexué, 9: dieu, ISO 5218:2004
  age       TEXT,     -- (cadet|junior|senior|veteran)
  status    TEXT,     -- pour isoler les confidents, serviteurs, ou pédants
  targets   INTEGER,  -- nombre de destinataires
  sources   INTEGER,  -- nombre d’émetteurs
  c         INTEGER,  -- out <c>, mombre de caractères dits
  w         INTEGER,  -- out <w>, mombre de mots dits
  l         INTEGER,  -- out <l>, nombre de vers dits
  sp        INTEGER,  -- out <sp>, nombre de répliques dites
  confs     INTEGER,  -- nombre de configurations
  confspeak INTEGER,  -- nombre de configurations où le personnage parle
  entries   INTEGER,  -- nombre d’entrées en scène (TODO)
  presence  INTEGER,  -- temps de présence (en caractères)
  participation INTEGER,  -- temps de participation, scènes où le personnage parle (en caractères)
  croles    INTEGER,  -- presence totale de tous les personnage en nombre de signes
  cspeakers INTEGER,  -- nombre de personnages parlants durant les scènes où

  PRIMARY KEY(id ASC)
);
CREATE UNIQUE INDEX role_who ON role(play, code);
CREATE UNIQUE INDEX role_ord ON role(play, ord);
CREATE INDEX role_c ON role(c);
CREATE INDEX role_presence ON role(presence);
