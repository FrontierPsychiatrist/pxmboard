CREATE TABLE pxm_badword
(
    bw_name        TEXT PRIMARY KEY,
    bw_replacement TEXT NOT NULL DEFAULT ''
);

INSERT INTO pxm_badword (bw_name, bw_replacement)
VALUES ('fuck', '****');


CREATE TABLE pxm_skin
(
    s_id         SMALLINT NOT NULL,
    s_fieldname  TEXT     NOT NULL DEFAULT '',
    s_fieldvalue TEXT     NOT NULL DEFAULT '',
    PRIMARY KEY (s_id, s_fieldname)
);

INSERT INTO pxm_skin (s_id, s_fieldname, s_fieldvalue)
VALUES (1, 'name', 'PXM Xsl Template'),
       (1, 'dir', 'pxm'),
       (1, 'type', 'Xslt'),
       (1, 'frame_top', '40'),
       (1, 'frame_bottom', '40'),
       (1, 'quoteprefix', '<font color="#808080">'),
       (1, 'quotesuffix', '</font>'),
       (1, 'tgfx_lastc', '<img src="images/lc.gif" width="8" height="22" border="0"/>'),
       (1, 'tgfx_midc', '<img src="images/mc.gif" width="8" height="22" border="0"/>'),
       (1, 'tgfx_empty', '<img src="images/empty.gif" width="8" height="22" border="0"/>'),
       (1, 'tgfx_noc', '<img src="images/nc.gif" width="8" height="22" border="0"/>'),
       (2, 'name', 'PXM Smarty Template'),
       (2, 'dir', 'smarty'),
       (2, 'type', 'Smarty'),
       (2, 'frame_top', '40'),
       (2, 'frame_bottom', '40'),
       (2, 'quoteprefix', '<font color="#808080">'),
       (2, 'quotesuffix', '</font>'),
       (2, 'tgfx_lastc', '<img src="images/lc.gif" width="8" height="22" border="0"/>'),
       (2, 'tgfx_midc', '<img src="images/mc.gif" width="8" height="22" border="0"/>'),
       (2, 'tgfx_noc', '<img src="images/nc.gif" width="8" height="22" border="0"/>'),
       (2, 'tgfx_empty', '<img src="images/empty.gif" width="8" height="22" border="0"/>'),
       (3, 'name', 'JSON Template'),
       (3, 'dir', 'pxm'),
       (3, 'type', 'Json'),
       (3, 'frame_top', '40'),
       (3, 'frame_bottom', '40'),
       (3, 'quoteprefix', '<font color="#808080">'),
       (3, 'quotesuffix', '</font>'),
       (3, 'tgfx_lastc', '<img src="images/lc.gif" width="8" height="22" border="0"/>'),
       (3, 'tgfx_midc', '<img src="images/mc.gif" width="8" height="22" border="0"/>'),
       (3, 'tgfx_empty', '<img src="images/empty.gif" width="8" height="22" border="0"/>'),
       (3, 'tgfx_noc', '<img src="images/nc.gif" width="8" height="22" border="0"/>');;

CREATE TABLE pxm_board
(
    b_id             SERIAL PRIMARY KEY,
    b_name           TEXT     NOT NULL DEFAULT '',
    b_description    TEXT     NOT NULL DEFAULT '',
    b_position       INT      NOT NULL DEFAULT 0,
    b_active         BOOLEAN  NOT NULL DEFAULT true,
    b_lastmsgtstmp   INT      NOT NULL DEFAULT 0,
    b_skinid         SMALLINT NOT NULL DEFAULT 1 REFERENCES pxm_skin (s_id),
    b_timespan       SMALLINT NOT NULL DEFAULT 100,
    b_threadlistsort TEXT     NOT NULL DEFAULT '',
    b_parsestyle     BOOLEAN  NOT NULL DEFAULT false,
    b_parseurl       BOOLEAN  NOT NULL DEFAULT false,
    b_parseimg       BOOLEAN  NOT NULL DEFAULT false,
    b_replacetext    BOOLEAN  NOT NULL DEFAULT false
);

CREATE TABLE pxm_banner
(
    ba_id         SERIAL PRIMARY KEY,
    ba_boardid    INT  NOT NULL DEFAULT 0 REFERENCES pxm_board (b_id),
    ba_code       TEXT NOT NULL,
    ba_start      INT  NOT NULL DEFAULT 0,
    ba_expiration INT  NOT NULL DEFAULT 0,
    ba_views      INT  NOT NULL DEFAULT 0,
    ba_maxviews   INT  NOT NULL DEFAULT 0
);

CREATE INDEX pxm_banner_board_id ON pxm_banner (ba_boardid);

INSERT INTO pxm_banner (ba_id, ba_boardid, ba_code, ba_start, ba_expiration, ba_views, ba_maxviews)
VALUES (6, 0, '<img src="images/banner.jpg">', 1032213600, 0, 0, 0);

CREATE TABLE pxm_configuration
(
    c_id                  SERIAL PRIMARY KEY,
    c_banner              BOOLEAN  NOT NULL DEFAULT false,
    c_quickpost           BOOLEAN  NOT NULL DEFAULT false,
    c_guestpost           BOOLEAN  NOT NULL DEFAULT false,
    c_directregistration  BOOLEAN  NOT NULL DEFAULT false,
    c_uniquemail          BOOLEAN  NOT NULL DEFAULT false,
    c_countviews          BOOLEAN  NOT NULL DEFAULT false,
    c_dateformat          TEXT     NOT NULL DEFAULT '',
    c_timeoffset          SMALLINT NOT NULL DEFAULT 0,
    c_onlinetime          SMALLINT NOT NULL DEFAULT 0,
    c_closethreads        SMALLINT NOT NULL DEFAULT 0,
    c_usrperpage          INT      NOT NULL DEFAULT 0,
    c_msgperpage          INT      NOT NULL DEFAULT 0,
    c_msgheaderperpage    INT      NOT NULL DEFAULT 0,
    c_privatemsgperpage   INT      NOT NULL DEFAULT 0,
    c_thrdperpage         INT      NOT NULL DEFAULT 0,
    c_mailwebmaster       TEXT     NOT NULL default '',
    c_maxprofilepicsize   INT      NOT NULL DEFAULT 0,
    c_maxprofilepicwidth  SMALLINT NOT NULL DEFAULT 0,
    c_maxprofilepicheight SMALLINT NOT NULL DEFAULT 0,
    c_profileimgdir       TEXT     NOT NULL default '',
    c_usesignatures       BOOLEAN  NOT NULL DEFAULT false,
    c_skinid              SMALLINT NOT NULL DEFAULT 0,
    c_quotechar           CHAR     NOT NULL default '>',
    c_quotesubject        TEXT     NOT NULL default 'Re:',
    c_skindir             TEXT     NOT NULL default '',
    c_parseurl            BOOLEAN  NOT NULL DEFAULT false,
    c_parsestyle          BOOLEAN  NOT NULL DEFAULT false
);

INSERT INTO pxm_configuration (c_id, c_banner, c_quickpost, c_guestpost, c_directregistration,
                               c_uniquemail, c_countviews, c_dateformat, c_timeoffset, c_onlinetime,
                               c_closethreads, c_usrperpage, c_msgperpage, c_msgheaderperpage,
                               c_privatemsgperpage, c_thrdperpage, c_mailwebmaster,
                               c_maxprofilepicsize, c_maxprofilepicwidth, c_maxprofilepicheight,
                               c_profileimgdir, c_usesignatures, c_skinid, c_quotechar,
                               c_quotesubject, c_skindir, c_parseurl, c_parsestyle)
VALUES (1, 1, 1, 1, 1, 1, 1, 'j.m.Y H:i', 0, 300, 0, 10, 10, 50, 10, 20, '', 50000, 200, 250,
        'images/profile/', 1, 2, '>', 'Re:', 'skins/', 1, 1);

CREATE TABLE pxm_error
(
    e_id      SERIAL PRIMARY KEY,
    e_message TEXT NOT NULL DEFAULT ''
);

INSERT INTO pxm_error (e_id, e_message)
VALUES (1, 'ungültiger modus'),
       (2, 'nickname unbekannt'),
       (3, 'passwort ungültig'),
       (4, 'konnte session nicht anlegen'),
       (5, 'board id fehlt'),
       (6, 'message id ungültig'),
       (7, 'subject fehlt'),
       (8, 'konnte daten nicht einfügen'),
       (9, 'dieser thread ist geschlossen'),
       (10, 'thread id ungültig'),
       (11, 'sie sind bereits eingeloggt'),
       (12, 'sie sind nicht dazu berechtigt'),
       (13, 'konnte daten nicht löschen'),
       (14, 'diese nachricht ist bereits vorhanden'),
       (15, 'fehler beim upload des bildes'),
       (16, 'konnte email nicht verschicken'),
       (17, 'auf diese nachricht wurde bereits geantwortet'),
       (18, 'dieses board ist geschlossen'),
       (19, 'ergebnismenge zu groß bitte schränken sie die suche ein'),
       (20, 'user id ungültig'),
       (21, 'email ungültig'),
       (22, 'sie sind nicht angemeldet'),
       (23, 'konnte passwort nicht übernehmen'),
       (24, 'ihre daten stimmen nicht mit den gespeicherten überein'),
       (25, 'dieser nickname ist bereits vergeben'),
       (26, 'bitte geben sie ihren nickname ein');

CREATE TABLE pxm_forbiddenmail
(
    fm_adress TEXT PRIMARY KEY
);

CREATE TABLE pxm_thread
(
    t_id           SERIAL PRIMARY KEY,
    t_boardid      INTEGER NOT NULL DEFAULT 0,
    t_active       BOOLEAN NOT NULL DEFAULT false,
    t_fixed        BOOLEAN NOT NULL DEFAULT false,
    t_lastmsgtstmp INTEGER NOT NULL DEFAULT 0,
    t_lastmsgid    INTEGER NOT NULL DEFAULT 0,
    t_msgquantity  INTEGER NOT NULL DEFAULT 0,
    t_views        INTEGER NOT NULL DEFAULT 0
);

CREATE INDEX threadlist_last_msgtstmp ON pxm_thread (t_boardid, t_fixed, t_lastmsgtstmp);
CREATE INDEX thread_lastmsgtstmp ON pxm_thread (t_lastmsgtstmp);

CREATE TABLE pxm_user
(
    u_id                  BIGSERIAL PRIMARY KEY,
    u_nickname            TEXT UNIQUE NOT NULL DEFAULT '',
    u_password            TEXT        NOT NULL DEFAULT '',
    u_passwordkey         TEXT        NOT NULL DEFAULT '',
    u_ticket              TEXT        NOT NULL DEFAULT '',
    u_firstname           TEXT        NOT NULL DEFAULT '',
    u_lastname            TEXT        NOT NULL DEFAULT '',
    u_city                TEXT        NOT NULL DEFAULT '',
    u_publicmail          TEXT        NOT NULL DEFAULT '',
    u_privatemail         TEXT        NOT NULL DEFAULT '',
    u_registrationmail    TEXT        NOT NULL DEFAULT '',
    u_registrationtstmp   INTEGER     NOT NULL DEFAULT '0',
    u_msgquantity         INTEGER     NOT NULL DEFAULT '0',
    u_lastonlinetstmp     INTEGER     NOT NULL DEFAULT '0',
    u_profilechangedtstmp INTEGER     NOT NULL DEFAULT '0',
    u_imgfile             TEXT        NOT NULL DEFAULT '',
    u_signature           TEXT        NOT NULL DEFAULT '',
    u_profile_icq         INTEGER     NOT NULL DEFAULT '0',
    u_profile_url         TEXT        NOT NULL DEFAULT '',
    u_profile_hobby       TEXT        NOT NULL DEFAULT '',
    u_highlight           BOOLEAN     NOT NULL DEFAULT '0',
    u_status              BOOLEAN     NOT NULL DEFAULT '0',
    u_post                BOOLEAN     NOT NULL DEFAULT '1',
    u_edit                BOOLEAN     NOT NULL DEFAULT '1',
    u_admin               BOOLEAN     NOT NULL DEFAULT '0',
    u_visible             BOOLEAN     NOT NULL DEFAULT '1',
    u_skinid              SMALLINT    NOT NULL DEFAULT 1,
    u_frame_top           BOOLEAN     NOT NULL DEFAULT '0',
    u_frame_bottom        BOOLEAN     NOT NULL DEFAULT '0',
    u_threadlistsort      TEXT        NOT NULL DEFAULT '',
    u_timeoffset          SMALLINT    NOT NULL DEFAULT 0,
    u_parseimg            BOOLEAN     NOT NULL DEFAULT '0',
    u_replacetext         BOOLEAN     NOT NULL DEFAULT '1',
    u_privatenotification BOOLEAN     NOT NULL DEFAULT '0',
    u_showsignatures      BOOLEAN     NOT NULL DEFAULT '1'
);

CREATE INDEX user_lastonlinetstmp ON pxm_user (u_lastonlinetstmp);

CREATE TABLE pxm_message
(
    m_id            BIGSERIAL PRIMARY KEY,
    m_threadid      BIGINT REFERENCES pxm_thread (t_id),
    m_parentid      BIGINT REFERENCES pxm_message (m_id),
    m_userid        BIGINT REFERENCES pxm_user (u_id),
    m_usernickname  TEXT    NOT NULL DEFAULT '',
    m_usermail      TEXT    NOT NULL DEFAULT '',
    m_userhighlight BOOLEAN NOT NULL DEFAULT false,
    m_subject       TEXT    NOT NULL DEFAULT '',
    m_body          TEXT    NOT NULL,
    m_tstmp         INT     NOT NULL DEFAULT 0, -- TODO Use a timestamp?
    m_ip            INET    NOT NULL,
    m_notification  BOOLEAN NOT NULL DEFAULT false
);

CREATE INDEX pxm_message_timestamp ON pxm_message (m_tstmp);
CREATE INDEX pxm_message_thread ON pxm_message (m_threadid, m_tstmp);
CREATE INDEX pxm_message_thread_parent ON pxm_message (m_threadid, m_parentid);
CREATE INDEX pxm_message_user ON pxm_message (m_usernickname, m_tstmp);

CREATE TABLE pxm_moderator
(
    mod_userid  BIGINT  NOT NULL DEFAULT 0 REFERENCES pxm_user (u_id),
    mod_boardid INTEGER NOT NULL DEFAULT 0 REFERENCES pxm_board (b_id),
    PRIMARY KEY (mod_userid, mod_boardid)
);

CREATE INDEX moderator_boardid ON pxm_moderator (mod_boardid);

CREATE TABLE pxm_notification
(
    n_id          SERIAL PRIMARY KEY,
    n_message     TEXT NOT NULL,
    n_name        TEXT NOT NULL default '',
    n_description TEXT NOT NULL default ''
);

INSERT INTO pxm_notification (n_id, n_message, n_name, n_description)
VALUES (1, 'PXMBoard Registrierung', 'registration mail subject',
        'subject of the registration mail'),
       (2,
        'Sie wurden registriert.\nIhr Nickname lautet: %nickname%\nIhr Passwort lautet: %password%',
        'registration mail body',
        'body of the registration mail\navailable placeholders: %password%,%nickname%'),
       (3, 'PXMBoard Registrierung', 'registration declined mail subject',
        'subject of the registration declined mail'),
       (4, 'Sie wurden nich registriert.\nGrund: %reason%', 'registration declined mail body',
        'body of the registration declined mail\navailable placeholders: %nickname%,%reason%'),
       (5, 'Doppelanmeldungen sind unzulässig!', 'registration declined reason',
        'default reason for a declined registration'),
       (6, 'Anforderung eines neuen Passwortes', 'password request mail subject',
        'subject of the password request mail'),
       (7,
        'Rufen sie folgenden Link auf wenn sie ein neues Passwort benötigen http://localhost/pxmboard/pxmboard.php?mode=usersendpwd&key=%key%',
        'password request mail body',
        'body of the password request mail\navailable placeholders: %nickname%, %key%'),
       (8, 'PXMBoard', 'lost password mail subject', 'subject of the lost password mail'),
       (9, 'Ihr Passwort lautet %password%', 'lost password mail body',
        'body of the lost password mail\navailable placeholders: %nickname%, %password%'),
       (10, 'Wurde editiert', 'edit note',
        'edit note for messages\navailable placeholders: %nickname%, %date%'),
       (11, 'PXMBoard private Nachricht', 'private message mail subject',
        'subject of the new private message notification'),
       (12, 'Sie habe eine neue private Nachricht erhalten', 'private message mail body',
        'body of the new private message notification\navailable placeholders: %nickname%'),
       (13, 'PXMBoard neue Antwort', 'reply notification mail subject',
        'subject of the reply notification'),
       (14,
        'Der Nutzer %nickname% hat auf ihren Beitrag %subject% geantwortet.\npxmboard.php?mode=board&brdid=%boardid%&thrdid=%threadid%&msgid=%replyid%',
        'reply notification mail body',
        'body of the reply notification\navailable placeholders: %nickname%,%subject%,%id%,%replysubject%,%replyid%,%boardid%,%threadid%');

CREATE TABLE pxm_priv_message
(
    p_id         BIGSERIAL PRIMARY KEY,
    p_touserid   BIGINT   NOT NULL DEFAULT 0 REFERENCES pxm_user (u_id),
    p_fromuserid BIGINT   NOT NULL DEFAULT 0 REFERENCES pxm_user (u_id),
    p_subject    TEXT     NOT NULL DEFAULT '',
    p_body       TEXT     NOT NULL,
    p_tstmp      INTEGER  NOT NULL DEFAULT 0,
    p_ip         INET,
    p_tostate    SMALLINT NOT NULL DEFAULT 1,
    p_fromstate  SMALLINT NOT NULL DEFAULT 2
);

CREATE INDEX priv_message_tstmp ON pxm_priv_message (p_tstmp);
CREATE INDEX priv_message_inbox ON pxm_priv_message (p_fromuserid, p_touserid, p_tostate, p_tstmp);
CREATE INDEX priv_message_outbox ON pxm_priv_message (p_touserid, p_fromuserid, p_fromstate, p_tstmp);

CREATE TYPE PXM_PROFILE_ACCEPT_TYPE AS ENUM ('s', 'a', 'i');

CREATE TABLE pxm_profile_accept
(
    pa_name   TEXT PRIMARY KEY,
    pa_type   PXM_PROFILE_ACCEPT_TYPE NOT NULL default 's',
    pa_length SMALLINT                NOT NULL default 0
);

INSERT INTO pxm_profile_accept (pa_name, pa_type, pa_length)
VALUES ('icq', 'i', 0),
       ('url', 's', 100),
       ('hobby', 's', 100);

CREATE TABLE pxm_search
(
    se_id       SERIAL PRIMARY KEY,
    se_userid   BIGINT  NOT NULL REFERENCES pxm_user (u_id),
    se_message  TEXT    NOT NULL,
    se_nickname TEXT    NOT NULL,
    se_days     INTEGER NOT NULL,
    se_boardids TEXT    NOT NULL,
    se_tstmp    INTEGER NOT NULL
);

CREATE INDEX search_tstmp ON pxm_search (se_tstmp);

CREATE TABLE pxm_textreplacement
(
    tr_name        TEXT PRIMARY KEY,
    tr_replacement TEXT NOT NULL DEFAULT ''
);

INSERT INTO pxm_textreplacement (tr_name, tr_replacement)
VALUES (':-)', '<img src="images/smiley.gif"/>');
