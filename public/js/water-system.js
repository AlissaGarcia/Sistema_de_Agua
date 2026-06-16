// Sistema de Controle de Consumo de Água - Functions

function show(id) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('screen-' + id).classList.add('active');
  event.currentTarget.classList.add('active');
}

// Função para calcular consumo e valor
function calcularConsumo() {
  const leituraAnterior = parseFloat(document.getElementById('leitura-anterior')?.value || 0);
  const leituraAtual = parseFloat(document.getElementById('leitura-atual')?.value || 0);
  
  if (leituraAnterior && leituraAtual) {
    const consumoM3 = leituraAtual - leituraAnterior;
    const consumoL = consumoM3 * 1000;
    
    // Valores padrão (deve vir do servidor)
    const taxaFixa = 25.00;
    const limiteConsumo = 10000; // 10 m³ em litros
    const valorExcedente = 2.00; // por 1000 litros
    
    let total = taxaFixa;
    let excedente = 0;
    
    if (consumoL > limiteConsumo) {
      excedente = ((consumoL - limiteConsumo) / 1000) * valorExcedente;
      total = taxaFixa + excedente;
    }
    
    const resultadoEl = document.getElementById('consumo-resultado');
    if (resultadoEl) {
      resultadoEl.innerHTML = `
        <strong>Consumo calculado: ${consumoM3.toFixed(1)} m³ (${consumoL.toLocaleString()} litros)</strong><br>
        ${consumoL > limiteConsumo ? `Acima do limite de ${limiteConsumo/1000} m³ → Taxa fixa R$ ${taxaFixa.toFixed(2)} + R$ ${excedente.toFixed(2)} excedente = <strong>R$ ${total.toFixed(2)}</strong>` : `Dentro do limite → Taxa fixa: <strong>R$ ${total.toFixed(2)}</strong>`}
      `;
    }
  }
}

// Evento para campo de leitura
document.addEventListener('DOMContentLoaded', function() {
  const leituraAnterior = document.getElementById('leitura-anterior');
  const leituraAtual = document.getElementById('leitura-atual');
  
  if (leituraAnterior) {
    leituraAnterior.addEventListener('change', calcularConsumo);
  }
  if (leituraAtual) {
    leituraAtual.addEventListener('change', calcularConsumo);
  }
});

// Função para marcar como pago
function marcarPago(faturaId) {
  if (confirm('Marcar esta fatura como paga?')) {
    // Será implementado com AJAX/fetch
    console.log('Marcar como pago:', faturaId);
  }
}

// Função para enviar WhatsApp
function enviarWhatsApp(telefone, consumidor, valor) {
  const mensagem = `Olá ${consumidor}, sua fatura no valor de R$ ${valor} está disponível no sistema de controle de água.`;
  const url = `https://wa.me/${telefone}?text=${encodeURIComponent(mensagem)}`;
  window.open(url, '_blank');
}
