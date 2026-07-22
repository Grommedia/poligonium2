(function () {
    const initSection = (section) => {
        if (!section || section.dataset.vfxReady === '1') {
            return
        }

        section.dataset.vfxReady = '1'

        const cards = Array.from(section.querySelectorAll('[data-vfx-card]'))
        const modal = section.querySelector('[data-vfx-modal]')
        const player = section.querySelector('[data-vfx-player]')
        const modalTitle = modal ? modal.querySelector('[data-vfx-title]') : null
        const modalType = modal ? modal.querySelector('[data-vfx-type]') : null
        const modalContact = modal ? modal.querySelector('[data-vfx-contact]') : null
        let activeIndex = 0

        const playCardVideo = (card) => {
            const video = card.querySelector('video')

            if (video) {
                video.play().catch(function () {})
            }
        }

        const stopCardVideo = (card) => {
            const video = card.querySelector('video')

            if (!video) {
                return
            }

            video.pause()
            video.currentTime = 0
        }

        const clearPlayer = () => {
            if (!player) {
                return
            }

            player.pause()
            player.removeAttribute('src')
            player.src = ''
            player.load()
        }

        const openModal = (index) => {
            const card = cards[index]

            if (!card || !modal || !player || !card.dataset.vfxVideo) {
                return
            }

            activeIndex = index
            cards.forEach(stopCardVideo)

            if (modalTitle) {
                modalTitle.textContent = card.dataset.vfxTitle || ''
            }

            if (modalType) {
                modalType.textContent = card.dataset.vfxType || 'VFX Showreel'
            }

            if (modalContact) {
                modalContact.href = card.dataset.vfxContact || '/contact'
            }

            clearPlayer()
            player.poster = card.dataset.vfxPoster || ''
            player.src = card.dataset.vfxVideo
            player.volume = 0.25
            player.muted = false
            modal.hidden = false
            modal.setAttribute('aria-hidden', 'false')
            document.documentElement.classList.add('poligonium-vfx-modal-open')
            player.play().catch(function () {})
        }

        const closeModal = () => {
            if (!modal) {
                return
            }

            clearPlayer()
            modal.hidden = true
            modal.setAttribute('aria-hidden', 'true')
            document.documentElement.classList.remove('poligonium-vfx-modal-open')
        }

        const openNext = () => {
            if (cards.length) {
                openModal((activeIndex + 1) % cards.length)
            }
        }

        const openPrev = () => {
            if (cards.length) {
                openModal((activeIndex - 1 + cards.length) % cards.length)
            }
        }

        section.addEventListener('pointerover', function (event) {
            const card = event.target.closest('.poligonium-vfx-card')

            if (card && section.contains(card)) {
                playCardVideo(card)
            }
        })

        section.addEventListener('pointerout', function (event) {
            const card = event.target.closest('.poligonium-vfx-card')

            if (card && section.contains(card) && !card.contains(event.relatedTarget)) {
                stopCardVideo(card)
            }
        })

        section.addEventListener('click', function (event) {
            const card = event.target.closest('[data-vfx-card]')

            if (card && section.contains(card)) {
                event.preventDefault()
                openModal(Number(card.dataset.vfxCard || 0))
                return
            }

            if (event.target.closest('[data-vfx-close]')) {
                closeModal()
                return
            }

            if (event.target.closest('[data-vfx-next]')) {
                openNext()
                return
            }

            if (event.target.closest('[data-vfx-prev]')) {
                openPrev()
            }
        })

        document.addEventListener('keydown', function (event) {
            if (!modal || modal.hidden) {
                return
            }

            if (event.key === 'Escape') {
                closeModal()
            }

            if (event.key === 'ArrowRight') {
                openNext()
            }

            if (event.key === 'ArrowLeft') {
                openPrev()
            }
        })
    }

    const init = () => {
        document.querySelectorAll('.poligonium-vfx-showreel').forEach(initSection)
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init)
    } else {
        init()
    }
})()
