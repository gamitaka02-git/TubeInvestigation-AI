document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.getElementById('hamburger-btn');
    const navMenu = document.getElementById('nav-menu');

    // ハンバーガーメニューの開閉
    hamburger.addEventListener('click', function () {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('open');
    });

    // メニューリンクをクリックしたら閉じる
    navMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            hamburger.classList.remove('active');
            navMenu.classList.remove('open');
        });
    });
    // FAQのアコーディオンアニメーション
    const faqs = document.querySelectorAll('.faq-item details');
    faqs.forEach(details => {
        const summary = details.querySelector('summary');
        const answer = details.querySelector('.faq-answer');

        summary.addEventListener('click', (e) => {
            e.preventDefault(); // デフォルトの挙動（即時開閉）を停止

            if (details.open) {
                // 閉じる動作
                const startHeight = answer.offsetHeight;
                answer.style.height = `${startHeight}px`;
                
                // 次のフレームで高さを0にする（トリガー）
                requestAnimationFrame(() => {
                    answer.style.height = '0px';
                    answer.style.opacity = '0';
                });

                // アニメーション完了後に属性を削除
                setTimeout(() => {
                    details.open = false;
                    answer.style.height = ''; // リセット
                    answer.style.opacity = '';
                }, 400); // CSSのtransition時間(0.4s)に合わせる
            } else {
                // 開く動作
                details.open = true;
                const targetHeight = answer.scrollHeight;
                answer.style.height = '0px';
                answer.style.opacity = '0';

                requestAnimationFrame(() => {
                    answer.style.height = `${targetHeight}px`;
                    answer.style.opacity = '1';
                });

                // アニメーション完了後にスタイルをクリア（レスポンシブ対応のため）
                setTimeout(() => {
                    answer.style.height = '';
                    answer.style.opacity = '';
                }, 400);
            }
        });
    });
});
