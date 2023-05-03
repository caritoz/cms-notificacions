// Reusable for multiple mutations
window._recursiveAddItem = (arr, payload) => {
    arr.forEach(item => {
        if (_.isEqual(item.id, payload.parent_id)) {
            item.replies.unshift(payload)
            return true;
        } else
            window._recursiveAddItem(item.threads, payload)
    });

    return arr;
}
window._recursiveRemoveItem = (arr, payload) => {
    arr.forEach(item => {
        if (_.isEqual(item.id, payload.parent_id)) {
            item.replies.shift(payload)
            return true;
        } else
            window._recursiveRemoveItem(item.threads, payload)
    });

    return arr;
}
window._recursiveUpdateItem = (arr, payload) => {
    arr.forEach((item, index) => {
        // if (_.isEqual(item.id, payload.id)) {
        if (item.id === payload.id) {
            arr[index] = payload
            return true;
        } else
            window._recursiveUpdateItem(item.threads, payload)
    });

    return arr;
}
