(() => {
    const on = (o, t, l) => o.addEventListener(t, l)
    const emit = (t, detail) => dispatchEvent(new CustomEvent(t, {detail}))

    const product = () => document.getElementById(`order_product`)
    const price = () => document.getElementById(`product_price`)

    const load = ev => {
        on(
            product(),
            `change`,
            ev => {
                const p = parseInt(ev.target.options[ev.target.selectedIndex].dataset.price)
                if ( ! isNaN(p)) {
                    price().innerHTML = new Intl.NumberFormat().format(p / 100) + ` &#8381;`
                }
                else {
                    price().innerHTML = ``
                }
            },
        )
    }

    on(window, `load`, load)
})()