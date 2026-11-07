// Copia las librerías JavaScript clásicas (jQuery, Bootstrap, SweetAlert2) desde
// node_modules a public/vendor. Se cargan como <script> normales (no módulos)
// porque los scripts en línea de las vistas Blade usan `$` y `bootstrap` de
// forma síncrona. Así quedan auto-hospedadas, con una sola versión de cada una.
import { copyFileSync, mkdirSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const target = resolve(root, 'public/vendor');

const files = [
    ['node_modules/jquery/dist/jquery.min.js', 'jquery.min.js'],
    ['node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'bootstrap.bundle.min.js'],
    ['node_modules/sweetalert2/dist/sweetalert2.all.min.js', 'sweetalert2.all.min.js'],
];

mkdirSync(target, { recursive: true });

for (const [from, to] of files) {
    copyFileSync(resolve(root, from), resolve(target, to));
}

console.log(`vendor: ${files.length} archivos copiados a public/vendor`);
