/**
 * YouTube Research Pro - Client Side Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const searchForm = document.querySelector('.search-form');

    // ===== 検索ページ用 =====

    /**
     * 選択されたモードに応じてプレースホルダーを切り替える
     */
    const updatePlaceholder = () => {
        if (!searchInput) return;
        const selectedMode = document.querySelector('input[name="mode"]:checked').value;
        if (selectedMode === 'channel') {
            searchInput.placeholder = "URL、ハンドル名(@...)、またはチャンネルIDを入力";
        } else {
            searchInput.placeholder = "リサーチしたい単語を入力...";
        }
    };

    /**
     * リサーチ開始ボタンの連打防止（UX向上）
     */
    if (searchForm) {
        searchForm.addEventListener('submit', () => {
            const submitBtn = searchForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = '取得中...';
            }
        });
    }

    // ラジオボタンの変更イベントを登録
    modeRadios.forEach(radio => {
        radio.addEventListener('change', updatePlaceholder);
    });

    // 初回読み込み時の実行
    if (searchInput) {
        updatePlaceholder();
    }

    // ===== 分析ページ用 =====
    const metaEl = document.getElementById('video-meta');
    if (metaEl) {
        window.VIDEO_META = {
            id: metaEl.dataset.id,
            title: metaEl.dataset.title,
            channel: metaEl.dataset.channel,
            views: parseInt(metaEl.dataset.views) || 0,
            likes: parseInt(metaEl.dataset.likes) || 0,
            comments: parseInt(metaEl.dataset.comments) || 0,
            thumbUrl: metaEl.dataset.thumb
        };
        initAnalysisPage();
    }
});

/**
 * 分析ページの初期化
 */
function initAnalysisPage() {
    const videoId = window.VIDEO_META.id;
    let aiDone = false;
    let thumbDone = false;

    // AI動画概要分析
    const resultDiv = document.getElementById('ai-result');
    const loadingDiv = document.getElementById('ai-loading');

    fetch(`analysis.php?v=${videoId}&action=analyze_ai`)
        .then(response => response.text())
        .then(data => {
            loadingDiv.classList.add('hidden');
            resultDiv.innerHTML = data;
            resultDiv.classList.remove('hidden');
            aiDone = true;
            checkExportReady();
        })
        .catch(error => {
            loadingDiv.classList.add('hidden');
            resultDiv.textContent = "分析中にエラーが発生しました。";
            resultDiv.classList.remove('hidden');
            aiDone = true;
            checkExportReady();
            console.error('Error:', error);
        });

    // サムネイル分析
    const thumbResultDiv = document.getElementById('thumb-result');
    const thumbLoadingDiv = document.getElementById('thumb-loading');

    fetch(`analysis.php?v=${videoId}&action=analyze_thumb`)
        .then(response => response.text())
        .then(data => {
            thumbLoadingDiv.classList.add('hidden');
            thumbResultDiv.innerHTML = data;
            thumbResultDiv.classList.remove('hidden');
            thumbDone = true;
            checkExportReady();
        })
        .catch(error => {
            thumbLoadingDiv.classList.add('hidden');
            thumbResultDiv.textContent = "サムネイル分析中にエラーが発生しました。";
            thumbResultDiv.classList.remove('hidden');
            thumbDone = true;
            checkExportReady();
            console.error('Error:', error);
        });

    /**
     * 両方の分析が完了したらエクスポートボタンを表示
     */
    function checkExportReady() {
        if (aiDone && thumbDone) {
            const exportBar = document.getElementById('export-bar');
            if (exportBar) exportBar.classList.remove('hidden');
            // ヘッダーのボタンも表示
            const pdfH = document.getElementById('btn-pdf-header');
            const mdH = document.getElementById('btn-md-header');
            if (pdfH) pdfH.classList.remove('hidden');
            if (mdH) mdH.classList.remove('hidden');
        }
    }

    // PDF保存ボタン（下部 + ヘッダー）
    document.querySelectorAll('#btn-pdf, #btn-pdf-header').forEach(btn => {
        btn.addEventListener('click', () => {
            window.print();
        });
    });

    // Markdown保存ボタン（下部 + ヘッダー）
    document.querySelectorAll('#btn-md, #btn-md-header').forEach(btn => {
        btn.addEventListener('click', () => {
            const markdown = generateMarkdown();
            downloadFile(markdown, `analysis_${videoId}.md`, 'text/markdown');
        });
    });
}

/**
 * AI分析結果をMarkdown形式に変換する
 */
function generateMarkdown() {
    const meta = window.VIDEO_META;
    const videoUrl = `https://www.youtube.com/watch?v=${meta.id}`;

    let md = `# 動画分析レポート\n\n`;
    md += `## 動画情報\n\n`;
    md += `- **タイトル**: ${meta.title}\n`;
    md += `- **チャンネル名**: ${meta.channel || '不明'}\n`;
    md += `- **URL**: ${videoUrl}\n`;
    md += `- **再生数**: ${Number(meta.views).toLocaleString()} 回\n`;
    md += `- **高評価**: ${Number(meta.likes).toLocaleString()}\n`;
    md += `- **コメント数**: ${Number(meta.comments).toLocaleString()}\n`;
    md += `- **サムネイル**: ${meta.thumbUrl}\n\n`;
    md += `---\n\n`;

    // AI分析結果
    const aiResult = document.getElementById('ai-result');
    if (aiResult && aiResult.innerHTML) {
        md += `## AIによる分析結果\n\n`;
        md += htmlToMarkdown(aiResult) + '\n\n';
        md += `---\n\n`;
    }

    // サムネイル分析結果
    const thumbResult = document.getElementById('thumb-result');
    if (thumbResult && thumbResult.innerHTML) {
        md += `## サムネイル分析\n\n`;
        md += htmlToMarkdown(thumbResult) + '\n\n';
    }

    return md;
}

/**
 * HTML要素の内容をMarkdownテキストに変換する
 */
function htmlToMarkdown(element) {
    let md = '';
    const nodes = element.childNodes;

    for (const node of nodes) {
        if (node.nodeType === Node.TEXT_NODE) {
            const text = node.textContent.trim();
            if (text) md += text;
            continue;
        }

        if (node.nodeType !== Node.ELEMENT_NODE) continue;

        const tag = node.tagName.toLowerCase();

        switch (tag) {
            case 'h3':
                md += `### ${node.textContent.trim()}\n\n`;
                break;
            case 'h4':
                md += `#### ${node.textContent.trim()}\n\n`;
                break;
            case 'p':
                md += convertInline(node) + '\n\n';
                break;
            case 'ul':
                for (const li of node.children) {
                    if (li.tagName.toLowerCase() === 'li') {
                        md += `- ${convertInline(li)}\n`;
                    }
                }
                md += '\n';
                break;
            case 'ol':
                let idx = 1;
                for (const li of node.children) {
                    if (li.tagName.toLowerCase() === 'li') {
                        md += `${idx}. ${convertInline(li)}\n`;
                        idx++;
                    }
                }
                md += '\n';
                break;
            case 'strong':
            case 'b':
                md += `**${node.textContent.trim()}**`;
                break;
            case 'em':
            case 'i':
                md += `*${node.textContent.trim()}*`;
                break;
            case 'br':
                md += '\n';
                break;
            default:
                md += htmlToMarkdown(node);
                break;
        }
    }

    return md.replace(/\n{3,}/g, '\n\n');
}

/**
 * インライン要素（strong, emなど）を含むテキストをMarkdownに変換
 */
function convertInline(element) {
    let result = '';
    for (const node of element.childNodes) {
        if (node.nodeType === Node.TEXT_NODE) {
            result += node.textContent;
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            const tag = node.tagName.toLowerCase();
            if (tag === 'strong' || tag === 'b') {
                result += `**${node.textContent}**`;
            } else if (tag === 'em' || tag === 'i') {
                result += `*${node.textContent}*`;
            } else if (tag === 'br') {
                result += '\n';
            } else {
                result += node.textContent;
            }
        }
    }
    return result.trim();
}

/**
 * テキストファイルとしてダウンロードする
 */
function downloadFile(content, filename, mimeType) {
    const blob = new Blob([content], { type: mimeType + ';charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}