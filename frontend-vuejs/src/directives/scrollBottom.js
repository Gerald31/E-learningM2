export default {
    bind(page, binding) {
        page.addEventListener('scroll', () => {
            if (Math.ceil(page.scrollHeight - page.scrollTop) <= Math.ceil(page.clientHeight * 1.5)) {
                if (binding.value) {
                    binding.value();
                }
            }
        });
    },
};
