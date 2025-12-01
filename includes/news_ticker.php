<?php
// News Ticker Component - Include this in all dashboard pages
if (!function_exists('getDBConnection')) {
    require_once dirname(__DIR__) . '/config/database.php';
}

$conn = getDBConnection();
$ads = $conn->query("SELECT * FROM ads WHERE active = 1 ORDER BY created_at DESC LIMIT 10");
$news = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 10");

$ticker_items = [];

// Add ads to ticker
if ($ads && $ads->num_rows > 0) {
    while ($ad = $ads->fetch_assoc()) {
        $ticker_items[] = [
            'type' => 'ad',
            'text' => htmlspecialchars($ad['title']),
            'link' => $ad['link_url'] ? htmlspecialchars($ad['link_url']) : '#'
        ];
    }
}

// Add news to ticker
if ($news && $news->num_rows > 0) {
    while ($item = $news->fetch_assoc()) {
        $ticker_items[] = [
            'type' => 'news',
            'text' => htmlspecialchars($item['title']),
            'badge' => htmlspecialchars($item['exam_type']),
            'link' => '#'
        ];
    }
}
?>
<div class="news-ticker-container">
    <div class="news-ticker-wrapper">
        <div class="ticker-label">📢 New Updates</div>
        <div class="ticker-content">
            <?php foreach ($ticker_items as $item): ?>
                <div class="ticker-item">
                    <?php if (isset($item['badge'])): ?>
                        <span class="ticker-badge"><?php echo $item['badge']; ?></span>
                    <?php endif; ?>
                    <a href="<?php echo $item['link']; ?>"><?php echo $item['text']; ?></a>
                </div>
            <?php endforeach; ?>
            <!-- Duplicate for seamless loop -->
            <?php foreach ($ticker_items as $item): ?>
                <div class="ticker-item">
                    <?php if (isset($item['badge'])): ?>
                        <span class="ticker-badge"><?php echo $item['badge']; ?></span>
                    <?php endif; ?>
                    <a href="<?php echo $item['link']; ?>"><?php echo $item['text']; ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

