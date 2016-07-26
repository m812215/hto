--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: date; Type: TABLE; Schema: public; Owner: hto; Tablespace: 
--

CREATE TABLE date (
    id integer NOT NULL,
    date pg_catalog.date,
    notes character varying(255),
    canceled integer
);


ALTER TABLE public.date OWNER TO hto;

--
-- Name: date_id_seq; Type: SEQUENCE; Schema: public; Owner: hto
--

CREATE SEQUENCE date_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.date_id_seq OWNER TO hto;

--
-- Name: date_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: hto
--

ALTER SEQUENCE date_id_seq OWNED BY date.id;


--
-- Name: date_person; Type: TABLE; Schema: public; Owner: hto; Tablespace: 
--

CREATE TABLE date_person (
    id integer NOT NULL,
    name character varying(255),
    facebook_id integer,
    date pg_catalog.date,
    from_time time without time zone,
    notes character varying(255),
    f_ctq_pilot integer,
    f_xdz_pilot integer,
    f_ctq_only integer,
    f_xdz_only integer,
    f_licenced integer,
    f_unhappy integer,
    f_i_aff integer,
    f_i_sl integer,
    f_i_radio integer,
    f_i_fs integer,
    f_i_tandem integer,
    f_s_aff integer,
    f_s_aff2 integer,
    f_s_sl integer,
    f_s_radio integer,
    f_s_fs integer,
    f_s_tandem integer
);


ALTER TABLE public.date_person OWNER TO hto;

--
-- Name: date_person_id_seq; Type: SEQUENCE; Schema: public; Owner: hto
--

CREATE SEQUENCE date_person_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.date_person_id_seq OWNER TO hto;

--
-- Name: date_person_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: hto
--

ALTER SEQUENCE date_person_id_seq OWNED BY date_person.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: hto
--

ALTER TABLE ONLY date ALTER COLUMN id SET DEFAULT nextval('date_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: hto
--

ALTER TABLE ONLY date_person ALTER COLUMN id SET DEFAULT nextval('date_person_id_seq'::regclass);


--
-- Data for Name: date; Type: TABLE DATA; Schema: public; Owner: hto
--

COPY date (id, date, notes, canceled) FROM stdin;
\.


--
-- Name: date_id_seq; Type: SEQUENCE SET; Schema: public; Owner: hto
--

SELECT pg_catalog.setval('date_id_seq', 29, true);


--
-- Data for Name: date_person; Type: TABLE DATA; Schema: public; Owner: hto
--

COPY date_person (id, name, facebook_id, date, from_time, notes, f_ctq_pilot, f_xdz_pilot, f_ctq_only, f_xdz_only, f_licenced, f_unhappy, f_i_aff, f_i_sl, f_i_radio, f_i_fs, f_i_tandem, f_s_aff, f_s_aff2, f_s_sl, f_s_radio, f_s_fs, f_s_tandem) FROM stdin;
\.


--
-- Name: date_person_id_seq; Type: SEQUENCE SET; Schema: public; Owner: hto
--

SELECT pg_catalog.setval('date_person_id_seq', 2101, true);


--
-- Name: date_person_pkey; Type: CONSTRAINT; Schema: public; Owner: hto; Tablespace: 
--

ALTER TABLE ONLY date_person
    ADD CONSTRAINT date_person_pkey PRIMARY KEY (id);


--
-- Name: date_pkey; Type: CONSTRAINT; Schema: public; Owner: hto; Tablespace: 
--

ALTER TABLE ONLY date
    ADD CONSTRAINT date_pkey PRIMARY KEY (id);


--
-- Name: public_date_date1_idx; Type: INDEX; Schema: public; Owner: hto; Tablespace: 
--

CREATE UNIQUE INDEX public_date_date1_idx ON date USING btree (date);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: date; Type: ACL; Schema: public; Owner: hto
--

REVOKE ALL ON TABLE date FROM PUBLIC;
REVOKE ALL ON TABLE date FROM hto;
GRANT ALL ON TABLE date TO hto;


--
-- Name: date_id_seq; Type: ACL; Schema: public; Owner: hto
--

REVOKE ALL ON SEQUENCE date_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE date_id_seq FROM hto;
GRANT ALL ON SEQUENCE date_id_seq TO hto;


--
-- Name: date_person; Type: ACL; Schema: public; Owner: hto
--

REVOKE ALL ON TABLE date_person FROM PUBLIC;
REVOKE ALL ON TABLE date_person FROM hto;
GRANT ALL ON TABLE date_person TO hto;


--
-- Name: date_person_id_seq; Type: ACL; Schema: public; Owner: hto
--

REVOKE ALL ON SEQUENCE date_person_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE date_person_id_seq FROM hto;
GRANT ALL ON SEQUENCE date_person_id_seq TO hto;


--
-- PostgreSQL database dump complete
--

