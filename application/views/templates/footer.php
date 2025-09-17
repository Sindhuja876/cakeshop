    </div>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p>© <?php echo date('Y'); ?> Sweet Delights Cake Shop. All rights reserved.</p>
            <div class="social-links">
                <a href="#" class="text-dark me-3"><i class="fab fa-facebook fa-2x"></i></a>
                <a href="#" class="text-dark me-3"><i class="fab fa-instagram fa-2x"></i></a>
                <a href="https://wa.me/1234567890?text=Hi%20I'm%20interested%20in%20your%20cakes" class="text-dark">
                    <i class="fab fa-whatsapp fa-2x"></i>
                </a>
            </div>
        </div>
    </footer>
<div id="whatsapp-widget" class="whatsapp-widget">
    <div class="whatsapp-header" onclick="toggleWhatsAppWidget()">
        <i class="fab fa-whatsapp"></i> Chat with us
        <span class="whatsapp-close" onclick="event.stopPropagation(); closeWhatsAppWidget()">×</span>
    </div>
    <div class="whatsapp-body">
        <div class="whatsapp-message">
            <p>Hello! 👋 How can we help you with your cake order today?</p>
        </div>
        <div class="whatsapp-quick-replies">
            <button class="whatsapp-reply-btn" onclick="sendQuickReply('View cake menu')">View cake menu</button>
            <button class="whatsapp-reply-btn" onclick="sendQuickReply('Place an order')">Place an order</button>
            <button class="whatsapp-reply-btn" onclick="sendQuickReply('Check delivery')">Check delivery</button>
        </div>
    </div>
    <div class="whatsapp-input">
        <input type="text" placeholder="Type a message..." id="whatsapp-input-field">
        <button onclick="sendWhatsAppMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>
<div class="whatsapp-float" onclick="openWhatsAppWidget()">
    <i class="fab fa-whatsapp"></i>
</div>

<script>
// WhatsApp widget functionality
function openWhatsAppWidget() {
    document.getElementById('whatsapp-widget').style.display = 'block';
    document.querySelector('.whatsapp-float').style.display = 'none';
    document.getElementById('whatsapp-input-field').focus();
}

function closeWhatsAppWidget() {
    document.getElementById('whatsapp-widget').style.display = 'none';
    document.querySelector('.whatsapp-float').style.display = 'block';
}

function toggleWhatsAppWidget() {
    const widget = document.getElementById('whatsapp-widget');
    if (widget.style.display === 'none') {
        openWhatsAppWidget();
    } else {
        closeWhatsAppWidget();
    }
}

function sendQuickReply(message) {
    // Add message to chat
    const chatBody = document.querySelector('.whatsapp-body');
    const userMessage = document.createElement('div');
    userMessage.className = 'whatsapp-message user-message';
    userMessage.innerHTML = `<p>${message}</p>`;
    chatBody.appendChild(userMessage);
    
    // Scroll to bottom
    chatBody.scrollTop = chatBody.scrollHeight;
    
    // Simulate response (in real implementation, this would call your backend)
    setTimeout(() => {
        const botMessage = document.createElement('div');
        botMessage.className = 'whatsapp-message';
        if (message === 'View cake menu') {
            botMessage.innerHTML = '<p>Here are our popular cakes: <a href="<?php echo site_url("shop"); ?>">View Full Menu</a></p>';
        } else if (message === 'Place an order') {
            botMessage.innerHTML = '<p>Great! You can order directly through our website or call us at (123) 456-7890</p>';
        } else {
            botMessage.innerHTML = '<p>We deliver within 10 miles of our shop. Delivery fee is $5.99.</p>';
        }
        chatBody.appendChild(botMessage);
        chatBody.scrollTop = chatBody.scrollHeight;
    }, 1000);
}

function sendWhatsAppMessage() {
    const inputField = document.getElementById('whatsapp-input-field');
    const message = inputField.value.trim();
    
    if (message) {
        sendQuickReply(message);
        inputField.value = '';
    }
}

// Enter key to send message
document.getElementById('whatsapp-input-field').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendWhatsAppMessage();
    }
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('assets/js/script.js'); ?>"></script>
</body>
<style>
/* WhatsApp widget styles */
.whatsapp-float {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: #25D366;
    color: white;
    border-radius: 50%;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    cursor: pointer;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.whatsapp-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 300px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    z-index: 1001;
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.whatsapp-header {
    background: #25D366;
    color: white;
    padding: 15px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.whatsapp-close {
    font-size: 20px;
    cursor: pointer;
}

.whatsapp-body {
    padding: 15px;
    height: 250px;
    overflow-y: auto;
    background: #f5f5f5;
}

.whatsapp-message {
    margin-bottom: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    background: white;
    max-width: 80%;
}

.whatsapp-message.user-message {
    margin-left: auto;
    background: #DCF8C6;
}

.whatsapp-quick-replies {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
}

.whatsapp-reply-btn {
    background: white;
    border: 1px solid #25D366;
    color: #25D366;
    padding: 8px;
    border-radius: 20px;
    text-align: center;
    cursor: pointer;
    font-size: 12px;
}

.whatsapp-reply-btn:hover {
    background: #25D366;
    color: white;
}

.whatsapp-input {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ddd;
    background: white;
}

.whatsapp-input input {
    flex: 1;
    border: none;
    padding: 8px;
    outline: none;
}

.whatsapp-input button {
    background: #25D366;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 50%;
    cursor: pointer;
}
</style>
</html>