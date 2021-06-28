const state = {
    content: ''
}

const mutations = {
    setContent (state, { content, timeout }) {
        state.content = content

        if (typeof timeout === 'undefined') {

        }

        setTimeout(() => (state.content = ''), timeout)
    }
}

export default {
    namespaced: true,
    state,
    mutations
}
