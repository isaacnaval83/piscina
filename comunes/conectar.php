<?php

function conectar(){
	return pg_connect("host=localhost user=piscina password=piscina dbname=piscina");
}