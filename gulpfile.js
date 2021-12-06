const { task, watch } = require("gulp");
const browserSync = require("browser-sync");

function reload() {
	return new Promise((res, rej) => {
		try {
			browserSync.reload();
			res();
		} catch (error) {
			rej(error);
		}
	});
}

function watchDev() {
	watch("**/*.php", reload);
}

task("serve", function () {
	browserSync({
		proxy: `127.0.0.1:8080`,
	});
	watchDev();
});