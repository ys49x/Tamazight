// favorites.js - Gestion externe des favoris
class FavoritesManager {
  constructor() {
    this.favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    this.showingFavorites = false;
  }

  toggleFavorite(message, isUserMessage = false) {
    if (isUserMessage) return;

    const index = this.favorites.findIndex(fav => fav.message === message);
    if (index === -1) {
      this.favorites.push({ message, date: new Date() });
    } else {
      this.favorites.splice(index, 1);
    }
    this.saveToLocalStorage();
    this.updateChatStars();
  }

  showFavorites(chatElement, loadHistoryCallback) {
    this.showingFavorites = !this.showingFavorites;
    
    if (this.showingFavorites) {
      chatElement.innerHTML = '<div class="favorites-title">⭐ Favoris</div>';
      
      if (this.favorites.length === 0) {
        chatElement.innerHTML += '<div>Aucun favori</div>';
        return;
      }

      this.favorites.forEach(fav => {
        const div = document.createElement('div');
        div.innerHTML = `${fav.message} <span class="favorite-star" onclick="favoritesManager.toggleFavorite('${fav.message.replace(/'/g, "\\'")}', false)">★</span>`;
        chatElement.appendChild(div);
      });
    } else {
      loadHistoryCallback();
    }
  }

  saveToLocalStorage() {
    localStorage.setItem('favorites', JSON.stringify(this.favorites));
  }

  isFavorite(message) {
    return this.favorites.some(fav => fav.message === message);
  }

  updateChatStars() {
    document.querySelectorAll('#chat div').forEach(div => {
      const starSpan = div.querySelector('.favorite-star');
      if (starSpan) {
        const message = div.textContent.replace(starSpan.textContent, '').trim();
        starSpan.textContent = this.isFavorite(message) ? '★' : '☆';
      }
    });
  }
}

// Instance globale
const favoritesManager = new FavoritesManager();

// Fonction pour ajouter un message au chat avec étoile
function addMessageToChatWithStar(message, isUser) {
  const chat = document.getElementById('chat');
  const div = document.createElement('div');
  
  if (!isUser) {
    const isFavorite = favoritesManager.isFavorite(message);
    div.innerHTML = `${message} <span class="favorite-star" onclick="favoritesManager.toggleFavorite('${message.replace(/'/g, "\\'")}', false)">${isFavorite ? '★' : '☆'}</span>`;
  } else {
    div.textContent = message;
  }
  
  chat.appendChild(div);
  chat.scrollTop = chat.scrollHeight;
}