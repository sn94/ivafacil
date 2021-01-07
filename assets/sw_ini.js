if ('serviceWorker' in navigator) {
    alert("service w");
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/ivafacil/assets/sw_novedades_clientes.js', { scope: "/ivafacil/admin/" }).then(function(registration) {
            // Registration was successful
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
            // registration failed :(
            console.log('ServiceWorker registration failed: ', err);
        });
    });
} else { alert("No soportado"); }