<?php
use Illuminate\Support\Facades\Route;
Route::get('/', fn() => response()->json(['name'=>'Kania Health Bridge API','docs'=>'See README.md and /docs folder','health'=>'/api/v1/health']));
