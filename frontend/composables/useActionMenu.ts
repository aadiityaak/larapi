export const useActionMenu = () => {
  const activeActionMenu = ref<number | null>(null)

  const toggleActionMenu = (id: number) => {
    if (activeActionMenu.value === id) {
      activeActionMenu.value = null
    } else {
      activeActionMenu.value = id
    }
  }

  const closeActionMenu = () => {
    activeActionMenu.value = null
  }

  // Handle keyboard navigation
  const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
      closeActionMenu()
    }
  }

  // Close action menu when clicking outside
  const handleDocumentClick = (event: Event) => {
    const target = event.target as HTMLElement
    // Don't close if clicking on action button or menu content
    if (!target.closest('[data-action-button]') && !target.closest('.action-menu-content')) {
      closeActionMenu()
    }
  }

  const setupEventListeners = () => {
    document.addEventListener('click', handleDocumentClick)
    document.addEventListener('keydown', handleKeydown)
    document.addEventListener('scroll', closeActionMenu, true)
  }

  const removeEventListeners = () => {
    document.removeEventListener('click', handleDocumentClick)
    document.removeEventListener('keydown', handleKeydown)
    document.removeEventListener('scroll', closeActionMenu, true)
  }

  return {
    activeActionMenu,
    toggleActionMenu,
    closeActionMenu,
    setupEventListeners,
    removeEventListeners
  }
}
