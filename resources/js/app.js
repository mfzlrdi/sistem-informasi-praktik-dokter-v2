import './bootstrap';

// Audio setup untuk notifikasi tanpa asset eksternal
let audioContext;
function playNotificationSound() {
  try {
    audioContext ??= new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gain = audioContext.createGain();
    oscillator.type = 'sine';
    oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(660, audioContext.currentTime + 0.12);
    gain.gain.setValueAtTime(0.0001, audioContext.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.16, audioContext.currentTime + 0.01);
    gain.gain.exponentialRampToValueAtTime(0.0001, audioContext.currentTime + 0.18);
    oscillator.connect(gain).connect(audioContext.destination);
    oscillator.start();
    oscillator.stop(audioContext.currentTime + 0.18);
  } catch {
    return;
  }
}

// Function untuk trigger animasi bell
function triggerBellAnimation() {
  const bellButton = document.getElementById('tombol-notifikasi');
  if (bellButton) {
    bellButton.classList.remove('animate-bell-ring');
    void bellButton.offsetWidth; // Trigger reflow untuk reset animation
    bellButton.classList.add('animate-bell-ring');
  }
}

// Toast notification system
function showToast(title, message, notifikasiId) {
  const container = document.getElementById('toast-notifikasi') || createToastContainer();
  
  const toast = document.createElement('div');
  toast.className = 'toast-item';
  toast.innerHTML = `
    <div class="toast-content">
      <div class="toast-title">${escapeHtml(title)}</div>
      <div class="toast-message">${escapeHtml(message)}</div>
    </div>
    <button type="button" class="toast-close" aria-label="Tutup notifikasi">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
  `;
  
  container.appendChild(toast);
  
  const closeButton = toast.querySelector('.toast-close');
  closeButton.addEventListener('click', () => {
    toast.classList.add('toast-exit');
    setTimeout(() => toast.remove(), 300);
  });
  
  // Auto-hide setelah 5 detik
  const timeout = setTimeout(() => {
    if (toast.parentNode) {
      toast.classList.add('toast-exit');
      setTimeout(() => toast.remove(), 300);
    }
  }, 5000);
  
  toast.addEventListener('mouseenter', () => clearTimeout(timeout));
}

function createToastContainer() {
  const container = document.createElement('div');
  container.id = 'toast-notifikasi';
  container.className = 'toast-container';
  document.body.appendChild(container);
  return container;
}

const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[char]);
const iconFor = (item) => item.judul_notifikasi?.toLowerCase().includes('resep') ? '<span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-amber-50 text-amber-600"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 3h9l3 3v15H6zM9 11h6M9 15h5"/></svg></span>' : '<span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-emerald-50 text-emerald-600"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 4h12v16H6zM9 8h6M9 12h6M9 16h4"/></svg></span>';

let unreadRequest;

function fetchNotifikasiBelumDibaca() {
  unreadRequest ??= fetch('/notifikasi/belum-dibaca', {
    headers: { Accept: 'application/json' },
    credentials: 'same-origin',
  })
    .then((response) => {
      if (!response.ok) throw new Error('Notifikasi tidak dapat dimuat.');
      return response.json();
    })
    .finally(() => {
      unreadRequest = undefined;
    });

  return unreadRequest;
}

function renderNotifikasi(items) {
  const list = document.getElementById('daftar-notifikasi');
  if (!list) return;

  if (!Array.isArray(items) || items.length === 0) {
    list.innerHTML = '<div class="px-5 py-10 text-center text-xs text-slate-400">Belum ada notifikasi baru.</div>';
    return;
  }

  list.innerHTML = items.map((item) => `<button type="button" data-notifikasi-id="${escapeHtml(item.id_notifikasi)}" data-pemenuhan="${escapeHtml(item.data_tambahan?.id_pemenuhan_resep ?? '')}" class="flex w-full gap-3 border-b border-slate-100 px-4 py-4 text-left transition hover:bg-slate-50">${iconFor(item)}<span class="min-w-0"><strong class="block truncate text-xs font-semibold text-slate-800">${escapeHtml(item.judul_notifikasi)}</strong><span class="mt-1 block line-clamp-2 text-[11px] leading-4 text-slate-500">${escapeHtml(item.pesan_notifikasi)}</span><small class="mt-1 block text-[10px] text-slate-400">${escapeHtml(item.dibuat_pada)}</small></span></button>`).join('');
}

async function refreshNotifikasi() {
  const badge = document.getElementById('jumlah-notifikasi');
  const panel = document.getElementById('panel-notifikasi');
  if (!badge) return;

  try {
    const result = await fetchNotifikasiBelumDibaca();
    const count = Number(result.jumlah) || 0;
    badge.textContent = count > 99 ? '99+' : String(count);
    badge.classList.toggle('hidden', count === 0);
    badge.classList.toggle('flex', count > 0);

    const button = document.getElementById('tombol-notifikasi');
    button?.setAttribute('aria-label', count > 0 ? `Notifikasi, ${count} belum dibaca` : 'Notifikasi');

    if (panel && !panel.hidden) renderNotifikasi(result.data);
  } catch {
    if (panel && !panel.hidden) {
      const list = document.getElementById('daftar-notifikasi');
      if (list) list.innerHTML = '<div class="px-5 py-10 text-center text-xs text-slate-400">Notifikasi belum dapat dimuat.</div>';
    }
  }
}

function csrfHeaders() {
  return {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
    Accept: 'application/json',
  };
}

function setupNotifikasi() {
  const root = document.getElementById('notifikasi-root');
  const button = document.getElementById('tombol-notifikasi');
  const panel = document.getElementById('panel-notifikasi');
  if (!root || !button || !panel) return;

  let openedByHover = false;
  let openedByFocus = false;
  const setPanelOpen = (open) => {
    const wasOpen = !panel.hidden;
    panel.hidden = !open;
    button.setAttribute('aria-expanded', String(open));
    button.setAttribute('aria-label', open ? 'Tutup notifikasi' : 'Buka notifikasi');
    if (open && !wasOpen) void refreshNotifikasi();
  };

  button.setAttribute('aria-expanded', 'false');
  button.addEventListener('click', (event) => {
    if (panel.hidden || event.detail === 0 || openedByHover || openedByFocus) {
      openedByHover = false;
      openedByFocus = false;
      setPanelOpen(true);
      return;
    }

    setPanelOpen(false);
  });

  root.addEventListener('pointerenter', (event) => {
    if (event.pointerType === 'mouse' || event.pointerType === 'pen') {
      openedByHover = true;
      openedByFocus = false;
      setPanelOpen(true);
    }
  });
  root.addEventListener('pointerleave', (event) => {
    if (event.pointerType !== 'touch' && !panel.contains(document.activeElement)) {
      openedByHover = false;
      openedByFocus = false;
      setPanelOpen(false);
    }
  });
  root.addEventListener('focusin', () => {
    if (panel.hidden) openedByFocus = true;
    setPanelOpen(true);
  });
  root.addEventListener('focusout', (event) => {
    if (!event.relatedTarget || !root.contains(event.relatedTarget)) {
      openedByHover = false;
      openedByFocus = false;
      setPanelOpen(false);
    }
  });

  document.addEventListener('click', (event) => {
    if (!root.contains(event.target)) {
      openedByHover = false;
      openedByFocus = false;
      setPanelOpen(false);
    }
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !panel.hidden) {
      button.focus({ preventScroll: true });
      openedByHover = false;
      openedByFocus = false;
      setPanelOpen(false);
    }
  });

  document.getElementById('tandai-semua-notifikasi')?.addEventListener('click', async () => {
    try {
      const response = await fetch('/notifikasi/baca-semua', {
        method: 'PATCH',
        headers: csrfHeaders(),
        credentials: 'same-origin',
      });
      if (!response.ok) throw new Error('Notifikasi tidak dapat diperbarui.');
      await refreshNotifikasi();
    } catch {
      showToast('Gagal memperbarui notifikasi', 'Silakan coba kembali.');
    }
  });

  document.getElementById('daftar-notifikasi')?.addEventListener('click', async (event) => {
    const item = event.target.closest('[data-notifikasi-id]');
    if (!item) return;

    try {
      const response = await fetch(`/notifikasi/${encodeURIComponent(item.dataset.notifikasiId)}/baca`, {
        method: 'PATCH',
        headers: csrfHeaders(),
        credentials: 'same-origin',
      });
      if (!response.ok) throw new Error('Notifikasi tidak dapat diperbarui.');
      window.location.assign(item.dataset.pemenuhan ? '/pemenuhan' : '/notifikasi');
    } catch {
      showToast('Gagal membuka notifikasi', 'Silakan coba kembali.');
    }
  });
}

function setupConfirmationModal() {
  const modal = document.getElementById('modal-konfirmasi-admin');
  if (!(modal instanceof HTMLDialogElement)) return;

  const title = document.getElementById('judul-modal-konfirmasi');
  const subtitle = document.getElementById('subjudul-modal-konfirmasi');
  const item = document.getElementById('item-modal-konfirmasi');
  const detail = document.getElementById('detail-modal-konfirmasi');
  const message = document.getElementById('pesan-modal-konfirmasi');
  const notice = document.getElementById('peringatan-modal-konfirmasi');
  const acknowledgement = document.getElementById('acknowledgement-modal-konfirmasi');
  const acknowledgementCheckbox = document.getElementById('checkbox-modal-konfirmasi');
  const acknowledgementText = document.getElementById('teks-acknowledgement-modal-konfirmasi');
  const cancelButton = document.getElementById('batal-modal-konfirmasi');
  const closeButton = document.getElementById('tutup-modal-konfirmasi');
  const confirmButton = document.getElementById('lanjut-modal-konfirmasi');
  const statusIcon = modal.querySelector('[data-confirm-icon="status"]');
  const dangerIcon = modal.querySelector('[data-confirm-icon="danger"]');

  if (
    !(title instanceof HTMLElement) ||
    !(subtitle instanceof HTMLElement) ||
    !(item instanceof HTMLElement) ||
    !(detail instanceof HTMLElement) ||
    !(message instanceof HTMLElement) ||
    !(notice instanceof HTMLElement) ||
    !(acknowledgement instanceof HTMLLabelElement) ||
    !(acknowledgementCheckbox instanceof HTMLInputElement) ||
    !(acknowledgementText instanceof HTMLElement) ||
    !(cancelButton instanceof HTMLButtonElement) ||
    !(closeButton instanceof HTMLButtonElement) ||
    !(confirmButton instanceof HTMLButtonElement)
  ) return;

  let pendingForm = null;
  let pendingSubmitter = null;
  const confirmedForms = new WeakSet();

  document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm-action')) return;
    if (confirmedForms.has(form)) {
      confirmedForms.delete(form);
      return;
    }

    event.preventDefault();
    pendingForm = form;
    pendingSubmitter = event.submitter instanceof HTMLElement
      ? event.submitter
      : form.querySelector('[type="submit"]');

    const tone = form.dataset.confirmTone === 'danger' ? 'danger' : 'teal';
    const requiresAcknowledgement = form.dataset.confirmRequiresAck === 'true';
    title.textContent = form.dataset.confirmTitle || 'Konfirmasi tindakan';
    subtitle.textContent = form.dataset.confirmSubtitle || 'Pastikan tindakan ini sesuai';
    item.textContent = form.dataset.confirmName || 'Item terpilih';
    detail.textContent = form.dataset.confirmDetail || '';
    detail.hidden = !detail.textContent;
    message.textContent = form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan?';
    acknowledgementText.textContent = form.dataset.confirmAckLabel || 'Saya memahami konsekuensi tindakan ini.';
    notice.dataset.tone = tone;
    if (statusIcon instanceof SVGElement) statusIcon.hidden = tone === 'danger';
    if (dangerIcon instanceof SVGElement) dangerIcon.hidden = tone !== 'danger';
    acknowledgement.hidden = !requiresAcknowledgement;
    acknowledgementCheckbox.checked = false;
    confirmButton.textContent = form.dataset.confirmConfirmLabel || 'Konfirmasi';
    confirmButton.disabled = requiresAcknowledgement;

    if (!modal.open) modal.showModal();
    cancelButton.focus({ preventScroll: true });
  });

  acknowledgementCheckbox.addEventListener('change', () => {
    confirmButton.disabled = !acknowledgement.hidden && !acknowledgementCheckbox.checked;
  });

  const closeModal = () => modal.close();
  cancelButton.addEventListener('click', closeModal);
  closeButton.addEventListener('click', closeModal);
  modal.addEventListener('click', (event) => {
    if (event.target === modal) closeModal();
  });
  modal.addEventListener('close', () => {
    if (!pendingForm) return;
    const submitter = pendingSubmitter;
    pendingForm = null;
    pendingSubmitter = null;
    submitter?.focus({ preventScroll: true });
  });

  confirmButton.addEventListener('click', () => {
    if (!pendingForm || (!acknowledgement.hidden && !acknowledgementCheckbox.checked)) return;

    const form = pendingForm;
    pendingForm = null;
    pendingSubmitter = null;
    confirmedForms.add(form);
    modal.close();
    form.requestSubmit();
  });
}

function subscribeToNotifikasi() {
  const userId = document.body.dataset.penggunaId;
  if (!userId || !window.Echo) return;

  window.Echo.private(`pengguna.${userId}`).listen('.notifikasi.baru', (data) => {
    triggerBellAnimation();
    playNotificationSound();
    showToast(data.judul_notifikasi || 'Notifikasi Baru', data.pesan_notifikasi || 'Ada notifikasi baru untuk Anda');
    void refreshNotifikasi();
  });
}

setupConfirmationModal();
setupNotifikasi();

if (document.body.dataset.penggunaId) {
  subscribeToNotifikasi();
  void refreshNotifikasi();
  window.setInterval(() => {
    if (document.visibilityState === 'visible') void refreshNotifikasi();
  }, 30_000);
}

