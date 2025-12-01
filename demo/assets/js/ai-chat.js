// AI Chat Functionality
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('aiChatContainer');
    const chatToggle = document.getElementById('aiChatToggle');
    const chatInput = document.getElementById('aiChatInput');
    const chatSend = document.getElementById('aiChatSend');
    const chatMessages = document.getElementById('aiChatMessages');
    
    let isMinimized = false;
    
    // Toggle chat window
    if (chatToggle) {
        chatToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            isMinimized = !isMinimized;
            chatContainer.classList.toggle('minimized', isMinimized);
        });
    }
    
    // Send message
    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';
        
        // Disable input while processing
        chatInput.disabled = true;
        chatSend.disabled = true;
        
        // Show typing indicator
        showTypingIndicator();
        
        try {
            // Call OpenRouter API via backend
            const response = await fetch('../api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            
            hideTypingIndicator();
            
            if (data.success && data.message) {
                addMessage(data.message, 'ai');
            } else {
                // Fallback to static response if API fails
                const fallbackResponse = getAIResponse(message);
                addMessage(fallbackResponse, 'ai');
                
                // Show error message if API key not configured
                if (data.error && data.message && data.message.includes('API key')) {
                    console.warn('OpenRouter API not configured. Using fallback responses.');
                }
            }
        } catch (error) {
            hideTypingIndicator();
            console.error('AI Chat Error:', error);
            // Fallback to static response
            const fallbackResponse = getAIResponse(message);
            addMessage(fallbackResponse, 'ai');
        } finally {
            // Re-enable input
            chatInput.disabled = false;
            chatSend.disabled = false;
            chatInput.focus();
        }
    }
    
    // Add message to chat
    function addMessage(text, type) {
        // Remove placeholder if it exists
        const placeholder = chatMessages.querySelector('.ai-chat-placeholder');
        if (placeholder) {
            placeholder.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-chat-message ${type}-message`;
        
        // Format text with line breaks
        const formattedText = text.replace(/\n/g, '<br>');
        messageDiv.innerHTML = formattedText;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Show typing indicator
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-chat-message';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Hide typing indicator
    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    // Get AI response (static responses for now - replace with API later)
    function getAIResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        // Educational responses
        if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
            return "Hello! I'm your AI study assistant. How can I help you today?";
        }
        
        if (lowerMessage.includes('formula') || lowerMessage.includes('equation')) {
            return "I can help you with formulas! Check out the Formulas section in your dashboard for Mathematics, Physics, and Chemistry formulas.";
        }
        
        if (lowerMessage.includes('homework') || lowerMessage.includes('assignment')) {
            return "You can find all your homework assignments in the Homework section. Make sure to track your progress!";
        }
        
        if (lowerMessage.includes('lecture') || lowerMessage.includes('video')) {
            return "All lectures and videos uploaded by your lecturers are available in the respective sections. You can download them anytime.";
        }
        
        if (lowerMessage.includes('progress') || lowerMessage.includes('status')) {
            return "Track your learning progress by updating the status of each material (Not Started, In Progress, Completed) in your dashboard.";
        }
        
        if (lowerMessage.includes('help') || lowerMessage.includes('how')) {
            return "I can help you with:\n- Finding study materials\n- Understanding formulas\n- Tracking your progress\n- Answering questions about your courses\n\nWhat would you like to know?";
        }
        
        if (lowerMessage.includes('math') || lowerMessage.includes('mathematics')) {
            return "For mathematics help, check the Calculator and Formulas sections. I can help explain concepts or guide you to resources.";
        }
        
        if (lowerMessage.includes('science') || lowerMessage.includes('physics') || lowerMessage.includes('chemistry')) {
            return "Science formulas and study materials are available in the Formulas section. Feel free to ask specific questions!";
        }
        
        // Default response
        return "I understand you're asking about: \"" + message + "\". I'm here to help with your studies! You can ask me about:\n- Study materials\n- Formulas and equations\n- Homework and assignments\n- Your learning progress\n\nHow can I assist you further?";
    }
    
    // Event listeners
    if (chatSend) {
        chatSend.addEventListener('click', sendMessage);
    }
    
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    // Welcome message
    if (chatMessages && chatMessages.children.length === 0) {
        setTimeout(() => {
            addMessage("Hello! I'm your AI study assistant. Ask me anything about your courses, materials, or formulas!", 'ai');
        }, 500);
    }
});

