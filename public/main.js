const shoppingListsUl = document.getElementById('shopping-lists-ul')
const newShoppingListButton = document.getElementById('new-shopping-list-button')
const signupButton = document.getElementById('signup-button')
const loginButton = document.getElementById('login-button')
const signoutButton = document.getElementById('signout-button')
const editModeButton = document.getElementById('edit-mode-button')
const newShoppingListForm = document.getElementById('new-shopping-list-form')
const signupForm = document.getElementById('signup-form')
const loginForm = document.getElementById('login-form')

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

function handleLoggedIn() {
    newShoppingListButton.hidden = false
    signupButton.hidden = true
    loginButton.hidden = true
    signoutButton.hidden = false
    editModeButton.hidden = false
}

function handleNotLoggedIn() {
    newShoppingListButton.hidden = true
    signupButton.hidden = false
    loginButton.hidden = false
    signoutButton.hidden = true
    editModeButton.hidden = true
    shoppingListsUl.innerHTML = '';
}

function addShoppinglistToUI(shoppingList) {
    const li = document.createElement('li')
    li.dataset.id = shoppingList.id

    const searchParams = new URLSearchParams({
        id: shoppingList.id
    })

    const nameAnchor = document.createElement('a')
    nameAnchor.classList.add('edit-mode-inactive')
    nameAnchor.href = `/public/shopping-list/index.html?${searchParams}`
    nameAnchor.textContent = shoppingList.name

    const editNameInput = document.createElement('input')
    editNameInput.classList.add('edit-mode-active')
    editNameInput.type = 'text'
    editNameInput.required = true
    editNameInput.name = `name`
    editNameInput.defaultValue = shoppingList.name
    editNameInput.ariaLabel = editNameInput.title = `endre navn på handlelisten`

    editNameInput.addEventListener('change', async () => {
        const name = editNameInput.value

        const response = await fetch(`/src/api/shopping-list/index.php?${searchParams}`, {
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
            nameAnchor.textContent = name
        }
    })

    const moveUpButton = document.createElement('button')
    moveUpButton.classList.add('edit-mode-active')
    moveUpButton.classList.add('button-icon')
    moveUpButton.type = 'button'
    moveUpButton.ariaLabel = moveUpButton.title = 'flytt opp handlelisten'

    const moveUpButtonIcon = document.createElement('span')
    moveUpButtonIcon.textContent = '↑'
    moveUpButtonIcon.ariaHidden = true
    moveUpButton.append(moveUpButtonIcon)

    moveUpButton.addEventListener('click', async () => {
        const ids = [...shoppingListsUl.querySelectorAll('li')].map(li => Number(li.dataset.id))
        const indexOfId = ids.indexOf(shoppingList.id)

        if (indexOfId === -1 || indexOfId === 0) {return}

        ;[ids[indexOfId], ids[indexOfId - 1]] = [ids[indexOfId - 1], ids[indexOfId]]

        const response = await fetch(`/src/api/shopping-list/order/index.php`, {
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
    moveDownButton.ariaLabel = moveDownButton.title = 'flytt ned handlelisten'

    const moveDownButtonIcon = document.createElement('span')
    moveDownButtonIcon.textContent = '↓'
    moveDownButtonIcon.ariaHidden = true
    moveDownButton.append(moveDownButtonIcon)

    moveDownButton.addEventListener('click', async () => {
        const ids = [...shoppingListsUl.querySelectorAll('li')].map(li => Number(li.dataset.id))
        const indexOfId = ids.indexOf(shoppingList.id)

        if (indexOfId === -1 || indexOfId === ids.length - 1) {return}

        ;[ids[indexOfId], ids[indexOfId + 1]] = [ids[indexOfId + 1], ids[indexOfId]]

        const response = await fetch(`/src/api/shopping-list/order/index.php`, {
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
    deleteButton.ariaLabel = deleteButton.title = 'slett handlelisten'

    const deleteButtonIcon = document.createElement('span')
    deleteButtonIcon.textContent = '×'
    deleteButtonIcon.ariaHidden = true
    deleteButton.append(deleteButtonIcon)

    deleteButton.addEventListener('click', async () => {
        const response = await fetch(`/src/api/shopping-list/index.php?${searchParams}`, {
            method: 'DELETE'
        })

        const result = await response.json()

        if (result.success) {
            li.remove()
        }
    })

    li.addEventListener('click', () => {
        if (editModeActive) {return}

        nameAnchor.click()
    })

    li.append(nameAnchor, editNameInput, moveUpButton, moveDownButton, deleteButton)
    shoppingListsUl.append(li)
}

async function fetchShoppingLists() {
    const response = await fetch('/src/api/shopping-list/index.php')
    const result = await response.json()

    if (!result.success && response.status === 401) {
        handleNotLoggedIn()
        return
    }

    handleLoggedIn()

    const shoppingLists = result.data

    if (shoppingLists.length === 0) {

        return
    }

    shoppingListsUl.innerHTML = '';
    shoppingLists.forEach(addShoppinglistToUI)
}

fetchShoppingLists()

newShoppingListForm.addEventListener('submit', async event => {
    const {result, formData} = await submitFormWithJson(event)

    if (result.success) {
        event.target.closest('dialog').close()
        event.target.reset()

        addShoppinglistToUI({
            id: result.data.id,
            name: formData.name,
        })
    }
})

signupForm.addEventListener('submit', async event => {
    const {result} = await submitFormWithJson(event)

    if (result.success) {
        event.target.closest('dialog').close()
        event.target.reset()

        fetchShoppingLists()
    }
})

loginForm.addEventListener('submit', async event => {
    const {result} = await submitFormWithJson(event)

    if (result.success) {
        event.target.closest('dialog').close()
        event.target.reset()

        fetchShoppingLists()
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
    shoppingListsUl.classList.toggle('edit-mode', editModeActive)

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