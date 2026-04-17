<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TubeInvestigation AI - Youtubeリサーチを「確信」に変える</title>
    <meta name="description"
        content="YouTube リサーチ ツール『TubeInvestigation AI』は、独自のバズり度判定と最新AI分析で、企画力で伸びている「本当に価値のある動画」を5分で抽出。ノイズを排除し、リサーチを単なる作業から次なるヒットへの確信へと変える革新的なツールです。">
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts: Inter and Noto Sans JP -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Noto+Sans+JP:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">TubeInvestigation AI</div>
            <button class="hamburger" id="hamburger-btn" aria-label="メニューを開く">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav id="nav-menu">
                <a href="#features">3つの強み</a>
                <a href="#details">主な仕様</a>
                <a href="#faq">FAQ</a>
                <a href="#purchase" class="nav-cta">お宝動画を発掘する</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- 1. ファーストビュー -->
        <section class="hero">
            <!-- ヒーローセクション装飾アニメーション -->
            <div class="hero-decorative-bg">
                <!-- 要素1：大きく動く3重の円（左側テキストエリアの背景） -->
                <div class="deco-play-btn large-cyber-circle">
                    <div class="circle-outer-layer1"></div>
                    <div class="circle-outer-layer2"></div>
                    <div class="circle-inner-layer3"></div>
                    <div class="play-triangle"></div>
                </div>

                <!-- 要素2：大きく動く棒グラフ（右側画像エリア周辺） -->
                <div class="deco-bar-chart large-chart">
                    <div class="bar bar-1"></div>
                    <div class="bar bar-2"></div>
                    <div class="bar bar-3"></div>
                    <div class="bar bar-4"></div>
                    <div class="bar bar-5"></div>
                </div>
            </div>

            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">企画力で伸びている<br><span
                            class="highlight">「本当に価値のある動画」を5分で抜き出す！</span><br>
                    </h1>
                    <p class="hero-subtitle">
                        AIを使った分析を武器に、あなたのリサーチを単なる「作業」から、次のヒットへの<span class="keyword-highlight">「確信」</span>を生むクリエイティブな時間へと<span class="keyword-highlight">「革新」</span>する。
                    </p>
                    <div class="hero-cta">
                        <form action="checkout.php" method="POST">
                            <div class="cta-price-container">
                                <p class="tool-name highlight">『TubeInvestigation AI』</p>
                            </div>
                            <!-- 【CTA 1】 -->
                            <button type="submit" class="cta-button">『TubeInvestigation AI』でリサーチ地獄から今すぐ抜け出す</button>
                            <div class="cta-micro-copy">
                                <span>✅ 買い切り版 12,800円</span>
                                <span>✅ 動画の台本化特典付き</span>
                                <span>✅ 30日間全額返金保証</span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="image-placeholder">
                        <img src="img/header01.png" alt="ヘッダーイメージ">
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. 3つの強み -->
        <section id="features" class="features-section">
            <div class="container">
                <h2 class="section-title">『TubeInvestigation AI』の「3つの強み」</h2>
                <div class="alternating-rows">
                    <div class="alt-row">
                        <div class="alt-row-content">
                            <h3>1. 登録者数が少なくても企画力で爆伸びしている「お宝動画」が丸わかり</h3>
                            <p>「再生数が登録者数より圧倒的に多い動画＝バズり度」として自動算出し、純粋な「企画力で伸びている真の成功事例」だけを開始数分で抽出します。</p>
                        </div>
                        <div class="alt-row-image">
                            <div class="image-wrapper">
                                <img src="img/alt-img01.png" alt="独自のバズり度判定">
                            </div>
                        </div>
                    </div>
                    <div class="alt-row">
                        <div class="alt-row-content">
                            <h3>2. 真似しても伸びないノイズ動画を徹底排除し、勝率の高い企画だけを抽出</h3>
                            <p>3つのフィルター（期間・最低登録者数・再生時間）で絞り込むことでノイズを極限まで減らし、あなたの戦略に合致した価値の高い動画だけを射抜きます。</p>
                        </div>
                        <div class="alt-row-image">
                            <div class="image-wrapper">
                                <img src="img/alt-img02.png" alt="リサーチのノイズを徹底排除">
                            </div>
                        </div>
                    </div>
                    <div class="alt-row">
                        <div class="alt-row-content">
                            <h3>3. 最新AIがヒットの裏側を丸裸にし、あなたの企画をワンランク上の「勝てる企画」へ昇華</h3>
                            <p>最新のGemini APIが、動画のターゲット層からベネフィット、さらには改善ポイントまで爆速で言語化。</p>
                            <p>サムネイルも同時に分析してくれるので、あなたは削減できた時間で「勝率の高い企画作り」に集中できるようになります。</p>
                        </div>
                        <div class="alt-row-image">
                            <div class="image-wrapper">
                                <img src="img/alt-img03.png" alt="動画・サムネイル分析">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. 【CTA 2】 -->
        <section class="cta-area">
            <div class="container">
                <p class="cta-text">直感とデータが融合する、新しいリサーチ体験。<br>余った時間はすべて「最高の企画づくり」へ。</p>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="cta-button primary">お宝動画を発掘する</button>
                    <div class="cta-micro-copy white-text">
                        <span>✅ 買い切り版 12,800円</span>
                        <span>✅ 動画の台本化特典付き</span>
                        <span>✅ 30日間全額返金保証</span>
                    </div>
                </form>
            </div>
        </section>

        <!-- 4. ストーリー -->
        <section class="story-section">
            <div class="container">
                <div class="story-outer-box">
                    <h2 class="section-title">リサーチの「劇的変化」ストーリー</h2>
                    <div class="story-intro">
                        <h3>勘と根性に頼る「リサーチの迷路」からの脱出</h3>
                        <p>何時間も画面をスクロールし、「なぜこの動画が伸びたのか？」と首を傾げる日々はもう終わりです。</p>
                        <p class="story-highlight">『TubeInvestigation AI』を導入したその日から、<br>あなたのリサーチ体験はこう変わります。</p>
                    </div>
                    <div class="alternating-rows">
                        <div class="alt-row">
                            <div class="alt-row-content">
                                <span class="step-num">01</span>
                                <h4>5分で「価値の高い動画」に辿り着く圧倒的なスピード</h4>
                                <p>キーワードや急上昇から検索し、独自の「バズり度」でソートするだけ。数時間かかっていたリサーチが開始数分で完了します。</p>
                            </div>
                            <div class="alt-row-image">
                                <div class="image-wrapper">
                                    <img src="img/tool01.gif" alt="リサーチのスピード">
                                </div>
                            </div>
                        </div>
                        <div class="alt-row">
                            <div class="alt-row-content">
                                <span class="step-num">02</span>
                                <h4>ノイズのない、純度の高いデータ収集</h4>
                                <p>最低登録者数や再生時間のフィルターで不要なノイズを排除。あなたの戦略に合致した成功事例だけをピンポイントで抽出します。</p>
                            </div>
                            <div class="alt-row-image">
                                <div class="image-wrapper">
                                    <img src="img/tool02.gif" alt="ノイズのないデータ収集">
                                </div>
                            </div>
                        </div>
                        <div class="alt-row">
                            <div class="alt-row-content">
                                <span class="step-num">03</span>
                                <h4>最新AIと共に、ヒットの「真髄」を徹底分析</h4>
                                <p>気になる動画はそのままAI分析へ。AIの客観的データとあなたの知見を掛け合わせ、勝算のある独自企画を立案できます。</p>
                            </div>
                            <div class="alt-row-image">
                                <div class="image-wrapper">
                                    <img src="img/tool03.gif" alt="AIによる分析">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. 特典紹介セクション -->
        <section class="bonus-section">
            <div class="container">
                <div class="bonus-wrapper">
                    <div class="bonus-content">
                        <span class="bonus-badge">購入者限定 特別特典</span>
                        <p class="bonus-subtitle" style="font-size: 1.2rem; margin-bottom: 0.5rem; font-weight: bold;">リサーチの結果から「勝率の高い台本」を生み出す</p>
                        <h2 class="bonus-title" style="font-size: 1.8rem;">NotebookLMを使ったYouTube台本制作ロードマップ</h2>
                        <p class="bonus-desc">
                            リサーチ結果のデータをGoogleの『NotebookLM』に読み込ませ、精度の高いYouTube台本を制作する具体的なフローを解説した手順書が付属。<br>
                            「リサーチで終わらない」、あなたの企画を最短で形にするための必須ロードマップです。
                        </p>
                    </div>
                    <div class="bonus-image-area">
                        <div class="bonus-image" style="border-radius:12px; overflow:hidden; box-shadow:0 10px 20px rgba(0,0,0,0.1);">
                            <img src="img/special01.png" alt="特典イメージ">
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- 7. 仕様・特徴 -->
        <section id="details" class="details-section">
            <div class="container">
                <h2 class="section-title">『TubeInvestigation AI』の主な仕様</h2>
                <div class="details-intro">
                    <p>リサーチから企画立案までのフローを劇的に加速させるための多彩な機能を備えています。</p>
                </div>
                <div class="details-grid">
                    <div class="detail-group">
                        <h3>リサーチ・検索機能</h3>
                        <ul>
                            <li><strong>3つの検索アプローチ：</strong>「キーワード」「特定チャンネル」「急上昇（グローバル対応）」から目的に合わせて検索</li>
                            <li><strong>「バズり度」自動算出＆ソート：</strong>ワンクリックで「バズり度」「再生数」「高評価数」などで並び替え</li>
                            <li><strong>期間指定フィルター：</strong>24時間以内、7日以内など、短期・長期のトレンドを把握</li>
                            <li><strong>最低登録者・再生時間フィルター：</strong>ノイズを除外し、ショート/ロング動画を切り分け</li>
                        </ul>
                    </div>
                    <div class="detail-group">
                        <h3>分析・AI機能</h3>
                        <ul>
                            <li><strong>最新AIによる「勝因」の解剖：</strong>最新安定版のGemini APIと連携し、ヒットの裏側を言語化</li>
                            <li><strong>動画コンテンツ分析：</strong>ターゲット層、ベネフィット、改善ポイントを鋭く考察</li>
                            <li><strong>サムネイル分析：</strong>配色意図やテキスト構成などを視覚的な観点から分析しCTR向上のヒントに</li>
                            <li class="note">※言語の違う動画でも日本語で分析されるので、グローバルな視点でリサーチが可能です。</li>
                        </ul>
                    </div>
                    <div class="detail-group">
                        <h3>出力・保存機能</h3>
                        <ul>
                            <li><strong>CSV形式での保存：</strong>検索結果一覧をExcelで扱いやすいよう保存</li>
                            <li><strong>Markdown/PDF保存：</strong>AIの分析結果を即座にドキュメントとして保存し、チーム共有やAI連携に活用</li>
                        </ul>
                    </div>
                    <div class="detail-group">
                        <h3>システム・ライセンス仕様</h3>
                        <ul>
                            <li><strong>マルチOS対応：</strong>Windows 11、macOS（Intel/M1~M3 ネイティブ動作）両対応</li>
                            <li><strong>セキュアなローカル動作：</strong>軽量かつ安全。ユーザー自身のAPIキーを使用することで透明性を確保</li>
                            <li><strong>1ライセンス2台まで・セルフ認証解除：</strong>買い替え時もサポート連絡不要で即座にPC移行可能</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. 30日間全額返金保証セクション -->
        <section class="guarantee-section">
            <div class="container">
                <div class="guarantee-box">
                    <div class="guarantee-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="#10B981" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="guarantee-content">
                        <h2>30日間 全額返金保証</h2>
                        <p>使ってみて1つもお宝動画が見つからなければ、30日以内なら全額返金します。<br><strong>あなたにリスクはありません。</strong></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. 【CTA 3】 -->
        <section class="cta-area secondary">
            <div class="container">
                <p class="cta-text">「なぜ伸びたのか」が手に取るように分かる。<br>最新AIの分析力を味方につけ、「狙ってヒットを生み出す」楽しさを。</p>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="cta-button primary">お宝動画を発掘する</button>
                    <div class="cta-micro-copy white-text">
                        <span>✅ 買い切り版 12,800円</span>
                        <span>✅ 動画の台本化特典付き</span>
                        <span>✅ 30日間全額返金保証</span>
                    </div>
                </form>
            </div>
        </section>

        <!-- 10. FAQ -->
        <section id="faq" class="faq-section">
            <div class="container">
                <h2 class="section-title">よくある質問（FAQ）</h2>
                <h3 class="faq-category-title">動作環境・ライセンスについて</h3>
                <div class="faq-list">
                    <div class="faq-item">
                        <details>
                            <summary>Q. 対応するOSを教えてください。<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. Windows 11、およびmacOS（Intelプロセッサ / Apple Silicon搭載機）の両方に対応しています。Mac版はユニバーサルバイナリ形式のため、どのモデルでもネイティブに動作します。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. 1ライセンスで何台のPCまで使えますか？<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. 1つのライセンスにつき、最大2台のデバイスまで同時に認証して利用することができます。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. PCを買い替えた場合はどうすればいいですか？<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. ツール内の設定画面より、ご自身で認証解除を行うことができます。サポートの返信を待つ必要はなく、いつでも即座に新しいPCへ移行できます。</div></div>
                        </details>
                    </div>
                </div>

                <h3 class="faq-category-title">ネットワーク・セキュリティについて</h3>
                <div class="faq-list">
                    <div class="faq-item">
                        <details>
                            <summary>Q. インターネット環境は必要ですか？<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. はい、YouTube Data API v3やGemini APIを利用するため、通信環境が必要です。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. 初回起動時のセキュリティ警告について。<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. 個人開発ソフトウェアのためOSの保護機能が反応する場合があります。Windowsは「詳細情報」＞「実行」、Macは設定の「プライバシーとセキュリティ」から「このまま開く」を選択してください。</div></div>
                        </details>
                    </div>
                </div>

                <h3 class="faq-category-title">API・保証について</h3>
                <div class="faq-list">
                    <div class="faq-item">
                        <details>
                            <summary>Q. APIの利用制限はありますか？<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. YouTube APIは無料枠内で十分利用可能で、上限に達しても翌日リセットされます。Gemini APIは1日最大10回までの分析が無料枠で可能です。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. 30日間全額返金保証について教えてください。<svg class="faq-border-svg" preserveAspectRatio="none"><rect x="0" y="0" width="100%" height="100%" pathLength="100" /></svg></summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. ご購入から30日以内であれば、ツールを活用してお宝動画が見つからなかった場合に限り、お申し出により全額を返金いたします。</div></div>
                        </details>
                    </div>
                </div>
            </div>
        </section>

        <!-- 11. 最後の一歩 ＋ 【CTA 4】 -->
        <section class="closing-section">
            <div class="container">
                <h2 class="section-title">リサーチを<span class="keyword-highlight">「確信」</span>に変える、最後の一歩</h2>
                <div class="closing-content">
                    <h3>圧倒的なデータを武器に「勝てる動画」を作り出しましょう。</h3>
                    <p>YouTubeの海に眠る「本当に価値のある動画」は、もうあなたの目の前にあります。</p>
                </div>

                <div id="purchase" class="purchase-box">
                    <h3>⚠️ ご購入の前に必ずご確認ください</h3>
                    <p class="warning-intro">本ツールは以下の必須事項への同意が必要です。あらかじめご了承の上お進みください。</p>
                    <ol class="warning-list">
                        <li><strong>ライセンスキーの再発行不可</strong>：紛失時の再発行等は一切行っておりません。</li>
                        <li><strong>APIの別途用意</strong>：ご自身の「YouTube Data API v3」および「Gemini API」キーが必要です。</li>
                        <li><strong>返金保証の適用</strong>：30日間の全額返金保証は、所定の条件を満たした場合に適用されます。</li>
                    </ol>
                    <form action="checkout.php" method="POST">
                        <div class="cta-price-container" style="margin-top:20px; text-align:center;">
                            <p class="tool-name highlight">『TubeInvestigation AI』</p>
                        </div>
                        <button type="submit" class="cta-button final">規約に同意して『TubeInvestigation AI』を導入する</button>
                        <div class="cta-micro-copy">
                            <span>✅ 買い切り版 12,800円</span>
                            <span>✅ 動画の台本化特典付き</span>
                            <span>✅ 30日間全額返金保証</span>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-content">
            <ul class="footer-links">
                <li><a href="terms.php">利用規約</a></li>
                <li><a href="privacy.php">プライバシーポリシー</a></li>
                <li><a href="law.php">特定商取引法に基づく表記</a></li>
            </ul>
            <p>&copy; 2026 TubeInvestigation AI. All rights reserved.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
