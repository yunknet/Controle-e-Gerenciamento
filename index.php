<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madeira de Lei - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require 'conecta.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <p></p>
        <h2>Produtos</h2>
        
        <div class="card-columns">
            <div class="card">
                <img src="images/mesadejantar.webp" alt="Produto 1">
                <div class="card-body">
                    <h5 class="card-title">Mesa Sucupira</h5>                
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal1">Ver Detalhes</a>
                </div>
            </div>

            <div class="card">
                <img src="images/produto2.jpg" alt="Produto 2">
                <div class="card-body">
                    <h5 class="card-title">Mesa Redonda</h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal2">Ver Detalhes</a>
                </div>
            </div>

            <div class="card">
                <img src="images/mesaredonda.webp" alt="Produto 3">
                <div class="card-body">
                    <h5 class="card-title">Mesa Redonda (Jantar)</h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal3">Ver Detalhes</a>
                </div>
            </div>

            <div class="card">
                <img src="images/produto4.jpg" alt="Produto 4">
                <div class="card-body">
                    <h5 class="card-title">Cadeira</h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal4">Ver Detalhes</a>
                </div>
            </div>

            <div class="card">
                <img src="images/produto5.jpg" alt="Produto 5">
                <div class="card-body">
                    <h5 class="card-title">Painel</h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal5">Ver Detalhes</a>
                </div>
            </div>

            <div class="card">
                <img src="images/produto6.jpg" alt="Produto 6">
                <div class="card-body">
                    <h5 class="card-title">Guarda Roupa Casal Rústico</h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal6">Ver Detalhes</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal1Label">Mesa Sucupira</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Mesa para jantar com armação em aço forjado. Ideal para quem busca conforto e comodidade nas refeições.</p>
                    <ul>
                        <li>Material: Aço forjado e madeira Sucupira</li>
                        <li>Dimensões: 180x90x75cm</li>
                        <li>Cor: Natural</li>
                        <li>Preço: R$ 1.200,00</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="modal2Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal2Label">Mesa Redonda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Mesa para área externa com acabamento em jatobá. Ideal para quem busca beleza e estilo.</p>
                    <ul>
                        <li>Material: Jatobá</li>
                        <li>Dimensões: 120cm de diâmetro</li>
                        <li>Cor: Natural</li>
                        <li>Preço: R$ 950,00</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Mesa Redonda (Jantar)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Mesa para sala feita no formato vintage. Ideal para quem busca beleza e estilos retrô.</p>
                    <ul>
                        <li>Material: Madeira maciça</li>
                        <li>Dimensões: 150cm de diâmetro</li>
                        <li>Cor: Castanho escuro</li>
                        <li>Preço: R$ 900,00</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal4" tabindex="-1" aria-labelledby="modal4Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal4Label">Cadeira</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Cadeira de design retrô, ideal para ambientes clássicos e sofisticados.</p>
                    <ul>
                        <li>Material: Madeira e estofado em tecido</li>
                        <li>Dimensões: 90x50x45cm</li>
                        <li>Cor: Marrom com detalhes dourados</li>
                        <li>Preço: R$ 450,00</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal5" tabindex="-1" aria-labelledby="modal5Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal5Label">Painel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Painel decorativo com vilosidades, dando um toque requintado e elegante ao ambiente.</p>
                    <ul>
                        <li>Material: Madeira e resina</li>
                        <li>Dimensões: 100x70cm</li>
                        <li>Cor: Bege claro</li>
                        <li>Preço: R$ 350,00</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal6" tabindex="-1" aria-labelledby="modal6Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal6Label">Guarda Roupa Casal Rústico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Guarda Roupa Casal Rústico com Espelho Madeira Maciça Chicago.</p>
                    <ul>
                        <li>Material: Madeira maciça</li>
                        <li>Dimensões: 200x180x60cm</li>
                        <li>Cor: Madeira Natural</li>
                        <li>Preço: R$ 2.500,00</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
