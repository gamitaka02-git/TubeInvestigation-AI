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
                <a href="#features">4つの強み</a>
                <a href="#details">主な仕様</a>
                <a href="#faq">FAQ</a>
                <a href="#purchase" class="nav-cta">7日間無料で試してみる</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- 1. ファーストビュー (tldv.io風の1カラムレイアウト＆巨大操作動画) -->
        <section class="hero">
            <div class="hero-decorative-bg">
                <div class="deco-play-btn large-cyber-circle">
                    <div class="circle-outer-layer1"></div>
                    <div class="circle-outer-layer2"></div>
                    <div class="circle-inner-layer3"></div>
                </div>
            </div>

            <div class="container hero-container-stacked">
                <div class="hero-content-center">
                    <span class="hero-badge">🎁 7日間の無料トライアル受付中</span>
                    <h1 class="hero-title-center">
                        「本当に価値のある動画」の発掘から<br>
                        <span class="highlight">「勝てる台本」の作成まで
                        </span><br>
                        AIが一気通貫で加速する！
                    </h1>
                    <p class="hero-subtitle-center">
                        リサーチから台本作成までを最新AIで一本化。<br>
                        あなたの時間を単なる「作業」から、次のヒットへの<span class="keyword-highlight">「確信」</span>を生む時間へと<span class="keyword-highlight">「革新」</span>する。
                    </p>
                    
                    <div class="hero-cta-center">
                        <form action="checkout.php" method="POST">
                            <button type="submit" class="cta-button pulse-effect">7日間の無料体験を今すぐ始める</button>
                            <div class="cta-micro-copy-center">
                                <span>🔒 Stripeによる安全な決済登録</span>
                                <span>⚡ 無料期間中の解約なら料金は一切不要</span>
                                <span>💡 登録後、即座にライセンスキーを発行</span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- tldv.io風の超巨大デモ動画/GIF配置エリア -->
                <div class="hero-video-mockup">
                    <div class="mockup-frame">
                        <div class="mockup-header">
                            <span class="dot red"></span>
                            <span class="dot yellow"></span>
                            <span class="dot green"></span>
                            <span class="mockup-title">TubeInvestigation AI - 実際の操作画面</span>
                        </div>
                        <img src="img/tool01.gif" alt="TubeInvestigation AI 動作デモ" class="mockup-gif">
                    </div>
                    <div class="glow-effect"></div>
                </div>
            </div>
        </section>

        <!-- 2. 4つの強み -->
        <section id="features" class="features-section">
            <div class="container">
                <h2 class="section-title">『TubeInvestigation AI』の「4つの強み」</h2>
                <div class="alternating-rows">
                    <div class="alt-row">
                        <div class="alt-row-content">
                            <h3>1. 登録者数が少なくても企画力で爆伸びしている「本当に価値のある動画」が丸わかり</h3>
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
                    <div class="alt-row">
                        <div class="alt-row-content">
                            <h3>4. リサーチ結果をそのまま「勝てる台本」へ昇華するAI台本作成機能</h3>
                            <p>リサーチで見つけた「本当に価値のある動画」の分析データを活用し、最新AIがあなた専用の動画台本を自動生成。</p>
                            <p>「何を話せばいいか分からない」「構成が思いつかない」という台本作成の壁を、AIが一気に突破します。</p>
                        </div>
                        <div class="alt-row-image">
                            <div class="image-wrapper">
                                <img src="img/alt-img07.png" alt="AI台本作成機能">
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
                    <button type="submit" class="cta-button primary pulse-effect">7日間の無料体験を始める</button>
                    <div class="cta-micro-copy white-text">
                        <span>✅ 7日間無料体験（いつでもオンライン解約可能）</span>
                        <span>✅ 動画の台本化特典ロードマップ付き</span>
                        <span>✅ Stripeによる暗号化安全決済</span>
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
                        <div class="alt-row">
                            <div class="alt-row-content">
                                <span class="step-num">04</span>
                                <h4>分析結果から「あなただけの台本」をAIが自動生成</h4>
                                <p>リサーチと分析で得たデータと、あなたの指示（プロンプト）を元に、AIがあなた専用の動画台本を作成。「何を話せばいいか分からない」という壁を一瞬で突破し、リサーチから台本完成までをシームレスに実現します。</p>
                            </div>
                            <div class="alt-row-image">
                                <div class="image-wrapper">
                                    <img src="img/tool04.gif" alt="AI台本作成">
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
                        <span class="bonus-badge">特別特典プレゼント</span>
                        <p class="bonus-subtitle" style="font-size: 1.2rem; margin-bottom: 0.5rem; font-weight: bold;">リサーチの結果から「勝率の高い台本」を生み出す</p>
                        <h2 class="bonus-title" style="font-size: 1.8rem;">NotebookLMを使ったYouTube台本制作ロードマップ</h2>
                        <p class="bonus-desc">
                            リサーチ結果のデータをGoogleの『NotebookLM』に読み込ませ、精度の高いYouTube台本を制作する具体的なフローを解説した手順書が付属。<br>
                            「リサーチで終わらない」、あなたの企画を最短で形にするための必須ロードマップです。<br>
                            ここで作成した企画をプロンプトにすることで、よりあなたの意図に合わせた動画台本が作成可能です。
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
                            <li><strong>AIによる動画台本の自動生成：</strong>分析データとあなたの指示（プロンプト）をもとに、最新AIが動画台本（.md形式）を作成。構成に悩む時間をゼロにし、リサーチから台本完成までをワンストップで実現</li>
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

        <!-- 8. 無料体験と解約に関する安心設計セクション (返金保証から差し替え) -->
        <section class="trial-info-section">
            <div class="container">
                <div class="trial-info-box">
                    <div class="trial-info-header">
                        <span class="trial-badge">安心のフリートライアル設計</span>
                        <h2>7日間の無料トライアルについて</h2>
                    </div>
                    <div class="trial-info-grid">
                        <div class="info-card">
                            <h3>💳 クレジットカード決済の登録</h3>
                            <p>Stripeの安全な決済システムを利用し、初回登録時にカード番号の登録が必要です。7日間の無料期間が終了するまで一切の請求は発生しません。</p>
                        </div>
                        <div class="info-card">
                            <h3>🔄 いつでもオンライン解約可能</h3>
                            <p>「自分には合わなかった」と思われた場合は、管理画面等からいつでもワンクリックで解約手続きが可能です。解約のための面倒なサポート連絡などは不要です。</p>
                        </div>
                        <div class="info-card warning-highlight">
                            <h3>⚠️ 解約時の即時ライセンス停止ルール</h3>
                            <p><strong>解約手続きを完了された場合、その時点で即座にライセンスキーが解除され、本ツールはご利用いただけなくなります。</strong>（日割りでの残り期間の利用や返金はございません。体験期間の最終日まで利用されたい場合は、期間終了の間際での解約をおすすめします。）</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. 【CTA 3】 -->
        <section class="cta-area secondary">
            <div class="container">
                <p class="cta-text">「なぜ伸びたのか」が手に取るように分かる。<br>最新AIの分析力を味方につけ、「狙ってヒットを生み出す」楽しさを。</p>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="cta-button primary pulse-effect">7日間の無料体験を始める</button>
                    <div class="cta-micro-copy white-text">
                        <span>✅ 7日間無料体験（いつでもオンライン解約可能）</span>
                        <span>✅ 動画の台本化特典ロードマップ付き</span>
                        <span>✅ Stripeによる暗号化安全決済</span>
                    </div>
                </form>
            </div>
        </section>

        <!-- 10. FAQ (サブスク・トライアル・即時停止仕様) -->
        <section id="faq" class="faq-section">
            <div class="container">
                <h2 class="section-title">よくある質問（FAQ）</h2>
                
                <h3 class="faq-category-title">無料トライアル・ご請求について</h3>
                <div class="faq-list">
                    <div class="faq-item">
                        <details>
                            <summary>Q. 無料体験期間中に料金は発生しますか？</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. いいえ、登録から7日間の無料体験期間中に解約手続きを行っていただければ、料金は1円もかかりません。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. 無料期間が終わるとどうなりますか？</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. 7日間の無料体験期間が終了すると、自動的に月額サブスクリプション課金が開始されます。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. 解約はいつでもできますか？また、解約するとどうなりますか？</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. はい、いつでもオンラインで解約可能です。ただし、解約手続きが完了した時点で即座にライセンスキーが解除され、ツールはご利用いただけなくなりますのでご注意ください。（体験期間を最大限利用されたい場合は、期間終了直前の解約をおすすめします。）</div></div>
                        </details>
                    </div>
                </div>

                <h3 class="faq-category-title">動作環境・ライセンスについて</h3>
                <div class="faq-list">
                    <div class="faq-item">
                        <details>
                            <summary>Q. 対応するOSを教えてください。</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. Windows 11、およびmacOS（Intelプロセッサ / Apple Silicon搭載機）の両方に対応しています。Mac版はユニバーサルバイナリ形式のため、どのモデルでもネイティブに動作します。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. 1ライセンスで何台のPCまで使えますか？</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. 1つのライセンスにつき、最大2台のデバイスまで同時に認証して利用することができます。</div></div>
                        </details>
                    </div>
                </div>

                <h3 class="faq-category-title">ネットワーク・セキュリティ・APIについて</h3>
                <div class="faq-list">
                    <div class="faq-item">
                        <details>
                            <summary>Q. インターネット環境は必要ですか？</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. はい、YouTube Data API v3やGemini APIを利用するため、通信環境が必要です。</div></div>
                        </details>
                    </div>
                    <div class="faq-item">
                        <details>
                            <summary>Q. APIの利用制限はありますか？</summary>
                            <div class="faq-answer"><div class="faq-answer-content">A. YouTube APIは無料枠内で十分利用可能で、上限に達しても翌日リセットされます。Gemini APIの無料枠ではツール側で「Gemini 3.1 Flash Lite」に設定していただきますと、1日最大200回近くまで動画分析および台本作成が可能です。それ以外は1日最大10回程度です。APIの利用が制限された場合でも、制限は基本的に翌日にリセットされます。</div></div>
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
                    <h3>⚠️ ご登録の前に必ずご確認ください</h3>
                    <p class="warning-intro">本ツールは以下の必須事項への同意が必要です。あらかじめご了承の上お進みください。</p>
                    <ol class="warning-list">
                        <li><strong>APIの別途用意</strong>：ご自身の「YouTube Data API v3」および「Gemini API」キーが必要です。</li>
                        <li><strong>解約時の即時ライセンス無効化</strong>：解約手続き完了と同時にライセンスキーは即座に解除され、ツールは利用不可となります。</li>
                        <li><strong>動作環境の確認</strong>：Windows 11またはmacOSでのローカル動作に対応しています。</li>
                    </ol>
                    <form action="checkout.php" method="POST">
                        <div class="cta-price-container" style="margin-top:20px; text-align:center;">
                            <p class="tool-name highlight">『TubeInvestigation AI』</p>
                            <p class="subscription-price-notice" style="font-size: 1.1rem; color: #a1a1aa; margin-top: 5px;">7日間無料体験トライアル（体験終了後：自動更新月額サブスクリプション）</p>
                        </div>
                        <button type="submit" class="cta-button final pulse-effect">規約に同意して『7日間無料体験』を始める</button>
                        <div class="cta-micro-copy">
                            <span>✅ 7日間無料体験<br>（いつでもオンライン解約可能）</span>
                            <span>✅ 登録時に即時ライセンスキーを発行</span>
                            <span>✅ Stripeによる安全な決済登録</span>
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
