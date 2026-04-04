<?php
namespace App\Livewire;
use App\Models\PillarStat;
use Livewire\Component;
class SidebarBurningZone extends Component {
    public function render() {
        $burning = PillarStat::where('is_burning', true)->first();
        return view('livewire.sidebar-burning-zone', ['burning' => $burning]);
    }
}
