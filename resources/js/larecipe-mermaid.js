(function () {
    function initMermaid() {
        if (typeof mermaid === 'undefined') {
            return;
        }

        mermaid.initialize({ startOnLoad: false, theme: 'default' });

        document.querySelectorAll('pre code.language-mermaid').forEach(function (block) {
            const pre = block.parentElement;

            if (! pre) {
                return;
            }

            const diagram = document.createElement('div');
            diagram.className = 'mermaid';
            diagram.textContent = block.textContent;
            pre.replaceWith(diagram);
        });

        mermaid.run();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMermaid);
    } else {
        initMermaid();
    }
})();
