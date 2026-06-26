const path = require('path');
const fs = require('fs');
const glob = require('glob');
const chokidar = require('chokidar');
const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');

const BLOCKS_DIR = path.resolve(__dirname, './blocks/acf');
const OUTPUT_FILE = path.resolve(__dirname, './blocks/allowed-blocks.json');

console.log('🔍 BLOCKS_DIR:', BLOCKS_DIR);
console.log('🔍 Checking if directory exists:', fs.existsSync(BLOCKS_DIR));

/**
 * Generate the sms marine allowed blocks map automatically from PHP templates
 */
function generateSMSAllowedMap() {
	const SMSAllowedParents = {};

	const blockTemplates = glob.sync(`${BLOCKS_DIR}/**/render.php`, {
		ignore: '**/build/**'
	});
	console.log('📄 Found PHP files:', blockTemplates.length);

	blockTemplates.forEach((phpFile) => {
		const blockName = phpFile.split('/').slice(-3, -2)[0];
		const phpContent = fs.readFileSync(phpFile, 'utf8');

		// Match: $allowed_blocks = ['core/heading', 'core/paragraph'];
		const match = phpContent.match(/\$allowed_blocks\s*=\s*\[([^\]]+)\]/);
		if (!match) return;

		const allowed = match[1]
			.split(',')
			.map((s) => s.trim().replace(/['"\s]/g, ''))
			.filter(Boolean);

		allowed.forEach((b) => {
			if (!SMSAllowedParents[b]) SMSAllowedParents[b] = [];
			SMSAllowedParents[b].push(`sms/${blockName}`);
		});
	});

	if (!fs.existsSync(path.dirname(OUTPUT_FILE))) {
		fs.mkdirSync(path.dirname(OUTPUT_FILE), { recursive: true });
	}

	fs.writeFileSync(OUTPUT_FILE, JSON.stringify(SMSAllowedParents, null, 2));
	console.log(`✅ SMS Marine allowed block map updated: ${OUTPUT_FILE}`);
}

// Generate initial map
generateSMSAllowedMap();

// Get all PHP files (excluding build directories) and watch them explicitly
const phpFiles = glob.sync(`${BLOCKS_DIR}/**/render.php`, {
	ignore: '**/build/**'
});

// Only start the persistent PHP file watcher during development.
// When running a production build (`NODE_ENV=production`) we skip the watcher
// so that `npm run build` exits after compilation.
if (process.env.NODE_ENV === 'production') {
	console.log('⏭️  Skipping PHP file watcher in production (NODE_ENV=production)');
} else {
	if (phpFiles.length > 0) {
		const watcher = chokidar.watch(phpFiles, {
			ignoreInitial: true,
			persistent: true,
			awaitWriteFinish: {
				stabilityThreshold: 100,
				pollInterval: 100
			}
		});

		watcher
			.on('add', (filePath) => {
				generateSMSAllowedMap();
			})
			.on('change', (filePath) => {
				generateSMSAllowedMap();
			})
			.on('unlink', (filePath) => {
				generateSMSAllowedMap();
			})
			.on('error', (error) => {
				console.error('❌ Watcher error:', error);
			});
	} else {
		console.log('⚠️  No PHP files found to watch');
	}
}

/**
 * Shared Webpack config
 */
const sharedConfig = {
	...defaultConfig,
	externals: {
		jquery: 'jQuery',
	},
	resolve: {
		alias: {
			Images: path.resolve(__dirname, 'assets/images/'),
			Fonts: path.resolve(__dirname, 'assets/fonts/'),
			Assets: path.resolve(__dirname, 'assets/'),
		},
	},
};

const allConfig = [];
const files = glob.sync('./blocks/acf/**/src/style.scss');

files.forEach((file) => {
	const blockName = file.split('/').slice(-3, -2)[0];
	console.log('Found Block:', blockName);

	const entry = Object.assign({}, sharedConfig, {
		name: blockName,
		entry: {
			block: [
				`./blocks/acf/${blockName}/src/style.scss`,
				`./blocks/acf/${blockName}/src/editor.scss`,
				`./blocks/acf/${blockName}/src/view.js`,
			],
		},
		output: {
			path: path.resolve(__dirname, `./blocks/acf/${blockName}/build/`),
		},
	});

	allConfig.push(entry);
});

const primaryConfig = Object.assign({}, sharedConfig, {
	name: 'main',
	entry: {
		frontend: ['./assets/js/main.js', './assets/sass/main.scss'],
	},
	output: {
		path: path.resolve(__dirname, './assets/build'),
	},
});

const adminConfig = Object.assign({}, sharedConfig, {
	name: 'admin',
	entry: {
		admin: ['./assets/js/admin.js', './assets/sass/admin.scss'],
	},
	output: {
		path: path.resolve(__dirname, './assets/admin/build'),
	},
});

allConfig.push(primaryConfig);
allConfig.push(adminConfig);

module.exports = allConfig;
