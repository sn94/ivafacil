//Instalacion
self.addEventListener('install', function(event) {
    // Perform install steps

    console.log("Instalado");
});

self.addEventListener('activate', event => {
    console.log(event);
});