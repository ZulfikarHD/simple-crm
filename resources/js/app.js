import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;

// Initialize Lucide icons
createIcons({ icons });

Alpine.start();
