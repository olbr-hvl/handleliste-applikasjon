const noShoppingListItemsMessage = document.getElementById('no-shopping-list-items-message')
const shoppingListItemsUl = document.getElementById('shopping-list-items-ul')
const signoutButton = document.getElementById('signout-button')
const editModeButton = document.getElementById('edit-mode-button')
const removeBoughtButton = document.getElementById('remove-bought-button')
const addShoppinglistItemButton = document.getElementById('add-shopping-list-item-button')
const addShoppinglistItemForm = document.getElementById('add-shopping-list-item-form')

const windowSearchParams = new URLSearchParams(window.location.search)
const shoppingListId = windowSearchParams.get('id')

async function submitFormWithJson(event) {
    event.preventDefault()

    const form = event.target
    const url = form.action
    const method = form.method

    const formData = new FormData(form)
    // Gets rid of any form controls that haven't been filled out.
    // Note: this also gets rid of form controls which have intentionally not been filled out.
    const filteredFormData = Object.fromEntries([...formData].filter(([key, value]) => value !== ''))
    const body = JSON.stringify(filteredFormData)

    const headers = {
        'Content-Type': 'application/json'
    }

    const response = await fetch(url, {
        method,
        body,
        headers,
    })

    return {
        response,
        result: await response.json(),
        formData: filteredFormData,
    }
}

function handleNotLoggedIn() {
    window.location.href = '/public/index.html'
}

function addShoppinglistItemToUI(shoppingListItem) {
    const li = document.createElement('li')
    li.dataset.id = shoppingListItem.id

    const searchParams = new URLSearchParams({
        id: shoppingListItem.id
    })

    const nameSpan = document.createElement('span')
    nameSpan.classList.add('edit-mode-inactive')
    nameSpan.classList.add('name-span')
    nameSpan.textContent = shoppingListItem.name

    const nameCheckbox = document.createElement('input')
    nameCheckbox.classList.add('edit-mode-inactive')
    nameCheckbox.classList.add('name-checkbox')
    nameCheckbox.classList.add('screenreader-only')
    nameCheckbox.checked = shoppingListItem.bought
    nameCheckbox.type = 'checkbox'
    nameCheckbox.ariaLabel = 'Kjøpt'

    nameCheckbox.addEventListener('change', async () => {
        const bought = nameCheckbox.checked

        const response = await fetch(`/src/api/shopping-list/item/index.php?${searchParams}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                bought
            }),
        })

        const result = await response.json()

        if (result.success) {
            nameCheckbox.checked = bought

            updateRemoveBoughtButtonVisibility()
        }
    })

    const editNameInput = document.createElement('input')
    editNameInput.classList.add('edit-mode-active')
    editNameInput.type = 'text'
    editNameInput.required = true
    editNameInput.name = `name`
    editNameInput.defaultValue = shoppingListItem.name
    editNameInput.ariaLabel = editNameInput.title = `endre navn på varen`

    editNameInput.addEventListener('change', async () => {
        const name = editNameInput.value

        const response = await fetch(`/src/api/shopping-list/item/index.php?${searchParams}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name
            }),
        })

        const result = await response.json()

        if (result.success) {
            nameSpan.textContent = name
        }
    })

    const moveUpButton = document.createElement('button')
    moveUpButton.classList.add('edit-mode-active')
    moveUpButton.classList.add('button-icon')
    moveUpButton.type = 'button'
    moveUpButton.ariaLabel = moveUpButton.title = 'flytt opp varen'

    const moveUpButtonIcon = document.createElement('span')
    moveUpButtonIcon.textContent = '↑'
    moveUpButtonIcon.ariaHidden = true
    moveUpButton.append(moveUpButtonIcon)

    moveUpButton.addEventListener('click', async () => {
        const ids = [...shoppingListItemsUl.querySelectorAll('li')].map(li => Number(li.dataset.id))
        const indexOfId = ids.indexOf(shoppingListItem.id)

        if (indexOfId === -1 || indexOfId === 0) {return}

        ;[ids[indexOfId], ids[indexOfId - 1]] = [ids[indexOfId - 1], ids[indexOfId]]

        const response = await fetch(`/src/api/shopping-list/item/order/index.php`, {
            method: 'PATCH',
            body: JSON.stringify(ids)
        })

        const result = await response.json()

        if (result.success) {
            li.previousElementSibling.before(li)
        }
    })

    const moveDownButton = document.createElement('button')
    moveDownButton.classList.add('edit-mode-active')
    moveDownButton.classList.add('button-icon')
    moveDownButton.type = 'button'
    moveDownButton.ariaLabel = moveDownButton.title = 'flytt ned varen'

    const moveDownButtonIcon = document.createElement('span')
    moveDownButtonIcon.textContent = '↓'
    moveDownButtonIcon.ariaHidden = true
    moveDownButton.append(moveDownButtonIcon)

    moveDownButton.addEventListener('click', async () => {
        const ids = [...shoppingListItemsUl.querySelectorAll('li')].map(li => Number(li.dataset.id))
        const indexOfId = ids.indexOf(shoppingListItem.id)

        if (indexOfId === -1 || indexOfId === ids.length - 1) {return}

        ;[ids[indexOfId], ids[indexOfId + 1]] = [ids[indexOfId + 1], ids[indexOfId]]

        const response = await fetch(`/src/api/shopping-list/item/order/index.php`, {
            method: 'PATCH',
            body: JSON.stringify(ids)
        })

        const result = await response.json()

        if (result.success) {
            li.nextElementSibling.after(li)
        }
    })

    const deleteButton = document.createElement('button')
    deleteButton.classList.add('edit-mode-active')
    deleteButton.classList.add('button-icon')
    deleteButton.type = 'button'
    deleteButton.ariaLabel = deleteButton.title = 'fjern varen'

    const deleteButtonIcon = document.createElement('span')
    deleteButtonIcon.textContent = '×'
    deleteButtonIcon.ariaHidden = true
    deleteButton.append(deleteButtonIcon)

    deleteButton.addEventListener('click', async () => {
        const response = await fetch(`/src/api/shopping-list/item/index.php?${searchParams}`, {
            method: 'DELETE'
        })

        const result = await response.json()

        if (result.success) {
            li.remove()

            updateRemoveBoughtButtonVisibility()
            updateNoShoppingListItemsMessage()
        }
    })

    li.addEventListener('click', () => {
        if (editModeActive) {return}

        nameCheckbox.click()
    })

    li.append(nameCheckbox, nameSpan, editNameInput, moveUpButton, moveDownButton, deleteButton)
    shoppingListItemsUl.append(li)

    updateRemoveBoughtButtonVisibility()
    updateNoShoppingListItemsMessage()
}

async function fetchShoppingList() {
    const searchParams = new URLSearchParams({
        id: shoppingListId
    })

    const response = await fetch(`/src/api/shopping-list/index.php?${searchParams}`)
    const result = await response.json()

    if (!result.success && response.status === 401) {
        handleNotLoggedIn()
        return
    }

    const shoppingListName = result.data.name

    document.querySelectorAll('.shopping-list-name').forEach(element => {
        element.textContent = shoppingListName
    })

    document.title = shoppingListName
    addShoppinglistItemForm.action = `/src/api/shopping-list/item/index.php?${searchParams}`
}

async function fetchShoppingListItems() {
    const searchParams = new URLSearchParams({
        id: shoppingListId
    })

    const response = await fetch(`/src/api/shopping-list/item/index.php?${searchParams}`)
    const result = await response.json()

    if (!result.success && response.status === 401) {
        handleNotLoggedIn()
        return
    }

    const shoppingListItems = result.data

    updateNoShoppingListItemsMessage()

    if (shoppingListItems.length === 0) {return}

    shoppingListItemsUl.innerHTML = '';
    shoppingListItems.forEach(addShoppinglistItemToUI)
}

fetchShoppingList().then(fetchShoppingListItems)

function updateNoShoppingListItemsMessage() {
    noShoppingListItemsMessage.hidden = shoppingListItemsUl.childElementCount !== 0
}

function updateRemoveBoughtButtonVisibility() {
    removeBoughtButton.hidden = !shoppingListItemsUl.querySelector('li .name-checkbox:checked')
}

removeBoughtButton.addEventListener('click', () => {
    shoppingListItemsUl.querySelectorAll('li').forEach(async li => {
        if (!li.querySelector('.name-checkbox:checked')) {return}

        const searchParams = new URLSearchParams({
            id: li.dataset.id
        })

        const response = await fetch(`/src/api/shopping-list/item/index.php?${searchParams}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                bought: false
            }),
        })

        const result = await response.json()

        if (result.success) {
            li.querySelector('.name-checkbox').checked = false

            updateRemoveBoughtButtonVisibility()
        }
    })
})

function handleFormError(form, result) {
    const formErrorMessage = form.querySelector('.form-error-message')

    formErrorMessage.hidden = result.success
    formErrorMessage.textContent = !result.success ? result.error : ''
}

addShoppinglistItemForm.addEventListener('submit', async event => {
    const {result, formData} = await submitFormWithJson(event)

    handleFormError(event.target, result)

    if (result.success) {
        event.target.closest('dialog').close()
        event.target.reset()

        addShoppinglistItemToUI({
            id: result.data.id,
            name: formData.name,
            bought: false,
        })
    }
})

signoutButton.addEventListener('click', async () => {
    const response = await fetch('/src/api/authentication/signout.php')
    const result = await response.json()

    if (result.success) {
        handleNotLoggedIn()
    }
})

// When there is better browser support the command attribute for the button element might be able to be used instead of javascript. https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/button#command
const dialogOpenButtons = document.querySelectorAll('[data-dialog-open]')

dialogOpenButtons.forEach(dialogOpenButton => {
    dialogOpenButton.addEventListener('click', () => {
        const dialogId = dialogOpenButton.dataset.dialogOpen
        const dialog = document.getElementById(dialogId)

        dialog.showModal()
    })
})

const dialogCloseButtons = document.querySelectorAll('.dialog-close-button')

dialogCloseButtons.forEach(dialogCloseButton => {
    dialogCloseButton.addEventListener('click', () => {
        const dialog = dialogCloseButton.closest('dialog')

        dialog.close()
    })
})

let editModeActive = false

function updateEditModeVisibility(target) {
    shoppingListItemsUl.classList.toggle('edit-mode', editModeActive)

    target.querySelectorAll('.edit-mode-active').forEach(element => {
        element.hidden = !editModeActive
    })

    target.querySelectorAll('.edit-mode-inactive').forEach(element => {
        element.hidden = editModeActive
    })
}

editModeButton.addEventListener('click', () => {
    editModeActive = !editModeActive

    updateEditModeVisibility(document)
})

updateEditModeVisibility(document)

// Might need to handle attribute changes too if in the future the edit-mode-active or edit-mode-inactive class is changed while the element is already in the DOM
new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        mutation.addedNodes.forEach(addedNode => {
            if (addedNode.nodeType !== Node.ELEMENT_NODE) {return}

            updateEditModeVisibility(addedNode)
        })
    })
}).observe(document.body, {childList: true, subtree: true})