// Dropdown Menu Toggle
const menuToggle = document.getElementById('menu-toggle');
const userMenu = document.getElementById('user-menu');

if (menuToggle) {
    menuToggle.onclick = (e) => {
        e.stopPropagation();
        userMenu.classList.toggle('show');
    };
}

document.onclick = () => {
    if (userMenu) userMenu.classList.remove('show');
};

// Chat Functionality
const messageInput = document.getElementById('message-input');
const sendBtn = document.getElementById('send-btn');
const messagesArea = document.getElementById('messages-area');

if (messageInput) {
    // Send Message
    const sendMessage = () => {
        const message = messageInput.value.trim();
        if (message === '') return;

        const formData = new FormData();
        formData.append('receiver_id', receiver_id);
        formData.append('message', message);

        fetch('includes/send_message.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    messageInput.value = '';
                    loadMessages();
                }
            });
    };

    sendBtn.onclick = sendMessage;
    messageInput.onkeypress = (e) => {
        if (e.key === 'Enter') sendMessage();
    };

    // Load Messages
    const loadMessages = () => {
        fetch(`includes/get_messages.php?receiver_id=${receiver_id}`)
            .then(res => res.text())
            .then(data => {
                const shouldScroll = messagesArea.scrollTop + messagesArea.clientHeight === messagesArea.scrollHeight;
                messagesArea.innerHTML = data;
                if (shouldScroll) {
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                }
            });
    };

    // Auto Refresh
    setInterval(loadMessages, 3000);
    loadMessages();
    // Initial scroll to bottom
    setTimeout(() => {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }, 500);
}

// Contact Search
const contactSearch = document.getElementById('contact-search');
const contactsList = document.getElementById('contacts-list');

if (contactSearch) {
    contactSearch.onkeyup = () => {
        const query = contactSearch.value.toLowerCase();
        const contacts = contactsList.getElementsByClassName('contact-item');

        Array.from(contacts).forEach(contact => {
            const name = contact.querySelector('.contact-name').innerText.toLowerCase();
            if (name.includes(query)) {
                contact.style.display = 'flex';
            } else {
                contact.style.display = 'none';
            }
        });
    };
}
