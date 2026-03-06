const mix = require("laravel-mix");
const fs = require("fs");
const path = require("path");

mix.js("resources/js/app.js", "public/js").postCss(
    "resources/css/app.css",
    "public/css",
    [require("tailwindcss")],
);

mix.copyDirectory("resources/img", "public/img");

mix.js("resources/js/commons/table.js", "public/js/commons");
mix.js("resources/js/commons/form.js", "public/js/commons");

const modulesPath = "./resources/js/modules";

if (fs.existsSync(modulesPath)) {
    fs.readdirSync(modulesPath).forEach((moduleName) => {
        const moduleDir = path.join(modulesPath, moduleName);
        if (fs.lstatSync(moduleDir).isDirectory()) {
            fs.readdirSync(moduleDir).forEach((file) => {
                if (file.endsWith(".js")) {
                    mix.js(
                        `${moduleDir}/${file}`,
                        `public/js/modules/${moduleName}`,
                    );
                }
            });
        }
    });
}

if (mix.inProduction()) {
    mix.version();
}
