<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProdutoLista extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $produto;
    private $key;
    private $negocio;
    public function __construct($produto, $key, $negocio)
    {
        $this->produto = $produto;
        $this->key = $key;
        $this->negocio = $negocio;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $produto = $this->produto;
        $key = $this->key;
        $negocio = $this->negocio;
        return view('components.produto-lista',compact('produto','key','negocio'));
    }
}
