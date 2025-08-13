<!DOCTYPE html>
<html>
<head>
    <title>Error - <?= htmlspecialchars($this->getMessage()) ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans w-full h-screen">
    <div class="container mx-auto px-2 py-8">
        <!-- Error Header -->
        <div class="bg-gray-800 p-6 rounded-lg mb-6">
            <!-- Display error class and message -->
            <div class="text-2xl font-bold text-red-500 mb-3 break-all whitespace-normal">
                <?= get_class($this) ?>
            </div>
            <!-- Display error class and message -->
            <div class="text-lg mb-2 break-all whitespace-normal">
                <?= htmlspecialchars($this->getMessage()) ?>
            </div>
            <!-- Display file and line number -->
            <div class="text-gray-400 break-all whitespace-normal">
                <?= $this->getFile(); ?>:<?= $this->getLine(); ?>
            </div>
        </div>

        <!-- Code and Trace Section -->
        <div class="bg-gray-800 rounded-lg flex">
            <div class="w-1/3">
                <h2 class="text-xl font-bold pl-10 py-3">Stack trace</h2>
                <!-- Display each trace item -->
                <?php foreach ($lastTraces as $index => $trace): ?>
                    <a href="?trace=<?= $index ?>">
                        <button class="w-full text-left p-3 transition-colors break-words <?= $index === $traceIndex ? 'bg-red-700 font-bold' : 'bg-gray-800 hover:bg-gray-600' ?>">
                            <!-- Display trace index -->
                            <strong class="mr-2 <?= $index === $traceIndex ? 'text-gray-300' : 'text-gray-400' ?>"><?= $index + 1 ?>.</strong>
                            <!-- Display file and line number -->
                            <?php if (isset($trace['file'])): ?>
                                <code class="block break-words">
                                    <span class="break-words <?= $index === $traceIndex ? 'text-blue-900' : 'text-blue-400' ?>"><?= $trace['file'] ?></span>:<span class="text-yellow-300"><?= $trace['line'] ?></span>
                                </code>
                            <?php endif; ?>
                            <!-- Display function or method call -->
                            <code class="mt-1 block text-gray-200 break-words">
                                <?= ($trace['class'] ?? '') . ($trace['type'] ?? '') . ($trace['function'] ?? '') ?>
                            </code>
                        </button>
                    </a>
                <?php endforeach; ?>
            </div>
            <!-- Code content for the selected trace -->
            <div id="traceContent" class="w-2/3 bg-gray-700 p-4 rounded font-mono">
                <!-- Display the code lines for the selected trace -->
                <?php foreach ($lastTraces[$traceIndex]["code"] as $lineNum => $codeLine): ?>
                    <div class="<?= $lineNum + 1 == $lastTraces[$traceIndex]["line"] ? 'bg-red-900/50' : '' ?> flex">
                        <span class="text-gray-500 pr-4 select-none"><?= $lineNum + 1 ?></span>
                        <code class="flex-1"><?= $codeLine ?></code>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
