<?php
namespace App\Http\Livewire;

use Livewire\Component;

class AdvertisementComponent extends Component
{
public $advertisement;
public $qrCode;

public function mount($advertisement, $qrCode)
{
$this->advertisement = $advertisement;
$this->qrCode = $qrCode;
}

public function render()
{
return view('livewire.advertisement-component');
}
}
