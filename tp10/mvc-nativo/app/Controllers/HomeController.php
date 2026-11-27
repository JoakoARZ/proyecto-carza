<?php
namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->vista('home/index', ['titulo' => 'Inicio']);
    }
}
