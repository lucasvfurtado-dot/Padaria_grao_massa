from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time
import random
import os
import webbrowser


class TesteAutomatizadoProduto:
    def __init__(self, url_login, url_cadastro):
        self.url_login = url_login
        self.url_cadastro = url_cadastro
        self.diretorio_teste = "TesteCadastroProdutos"

        # Cria a pasta se não existir
        if not os.path.exists(self.diretorio_teste):
            os.makedirs(self.diretorio_teste)

        # Lista para armazenar resultados do relatório
        self.resultados_testes = []

        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")

        self.driver = webdriver.Chrome(options=chrome_options)
        self.wait = WebDriverWait(self.driver, 10)

        print("✓ Ambiente preparado e pasta 'TesteCadastroProdutos' verificada!")

    def realizar_login(self, cpf, senha):
        print("\n🔑 Realizando login no sistema...")
        try:
            self.driver.get(self.url_login)
            time.sleep(1)  # Pausa para ver a tela de login

            self.wait.until(EC.presence_of_element_located((By.NAME, "cpf"))).send_keys(cpf)
            time.sleep(1)

            self.driver.find_element(By.NAME, "senha").send_keys(senha)
            time.sleep(1)

            self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
            time.sleep(3)  # Aguarda 3 segundos após o login
            print("✓ Login efetuado com sucesso!")
        except Exception as e:
            print(f"✗ Erro ao tentar fazer o login: {e}")
            self.driver.quit()
            exit()

    def gerar_dados_aleatorios(self):
        # Nomes de produtos condizentes com uma padaria
        nomes_por_categoria = {
            "Pães": ["Pão Francês", "Pão de Queijo Tradicional", "Pão Italiano", "Pão de Forma Integral", "Pão Sírio"],
            "Bolos": ["Bolo de Cenoura com Chocolate", "Bolo de Fubá", "Bolo Red Velvet", "Bolo de Laranja", "Bolo Formigueiro"],
            "Salgados": ["Coxinha de Frango", "Esfirra de Carne", "Empada de Palmito", "Risole de Camarão", "Pastel de Queijo"],
            "Doces": ["Brigadeiro Gourmet", "Beijinho", "Sonho Recheado", "Palha Italiana", "Quindim"],
            "Bebidas": ["Café Expresso", "Suco de Laranja Natural", "Cappuccino", "Chá Gelado", "Água com Gás"],
        }
        categoria = random.choice(list(nomes_por_categoria.keys()))
        nome_produto = random.choice(nomes_por_categoria[categoria])

        codigo = f"COD{random.randint(1000, 9999)}"
        preco = f"{random.randint(3, 60)},{random.randint(0, 99):02d}"
        estoque = str(random.randint(0, 200))
        descricao = f"{nome_produto} - produto cadastrado via automação de testes."

        return {
            "nProduto": nome_produto,
            "nCodigo": codigo,
            "nCategoria": categoria,
            "nPreco": preco,
            "nEstoque": estoque,
            "nDescricao": descricao,
        }

    def tirar_screenshot(self, nome_arquivo):
        caminho = os.path.join(self.diretorio_teste, nome_arquivo)
        self.driver.save_screenshot(caminho)
        return nome_arquivo

    def gerar_relatorio_html(self):
        caminho_html = os.path.join(self.diretorio_teste, "dashboard.html")
        sucessos = sum(1 for r in self.resultados_testes if r['status'] == 'Sucesso')
        falhas = len(self.resultados_testes) - sucessos

        html_content = f"""
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard de Testes - Grão & Massa</title>
            <style>
                body {{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 20px; }}
                .container {{ max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }}
                h1 {{ color: #d35400; text-align: center; }}
                .summary {{ display: flex; justify-content: space-around; margin-bottom: 30px; padding: 15px; background: #e9ecef; border-radius: 5px; }}
                .card {{ text-align: center; }}
                .card h2 {{ margin: 0; font-size: 2em; }}
                .status-sucesso {{ color: #28a745; }}
                .status-falha {{ color: #dc3545; }}
                table {{ width: 100%; border-collapse: collapse; margin-top: 20px; }}
                th, td {{ padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }}
                th {{ background-color: #d35400; color: white; }}
                .img-link {{ color: #007bff; text-decoration: none; font-weight: bold; }}
                tr:hover {{ background-color: #f1f1f1; }}
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Relatório de Automação: Cadastro de Produtos</h1>
                <div class="summary">
                    <div class="card"><h3>Total Cadastros</h3><h2>{len(self.resultados_testes)}</h2></div>
                    <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                    <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th>Evidência</th>
                        </tr>
                    </thead>
                    <tbody>
        """

        for r in self.resultados_testes:
            cor_status = "status-sucesso" if r['status'] == 'Sucesso' else "status-falha"
            html_content += f"""
                <tr>
                    <td>{r['id']}</td>
                    <td>{r['nome']}</td>
                    <td>{r['categoria']}</td>
                    <td class="{cor_status}">{r['status']}</td>
                    <td><a class="img-link" href="{r['screenshot']}" target="_blank">Visualizar Screenshot</a></td>
                </tr>
            """

        html_content += """
                    </tbody>
                </table>
            </div>
        </body>
        </html>
        """

        with open(caminho_html, "w", encoding="utf-8") as f:
            f.write(html_content)

        return caminho_html

    def executar_teste_completo(self, quantidade):
        # ---> TEMPO DE PAUSA ENTRE OS CAMPOS (em segundos) <---
        pausa_digitacao = 0.5

        for i in range(quantidade):
            print(f"\n🚀 Iniciando cadastro {i+1} de {quantidade}...")
            dados = self.gerar_dados_aleatorios()
            status = "Falha"
            nome_print = ""

            try:
                self.driver.get(self.url_cadastro)
                time.sleep(1)  # Espera a página carregar

                # Preenchendo os dados devagar
                self.wait.until(EC.presence_of_element_located((By.NAME, "nProduto"))).send_keys(dados["nProduto"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nCodigo").send_keys(dados["nCodigo"])
                time.sleep(pausa_digitacao)

                # Categoria é um <select> — precisa usar a classe Select
                categoria_select = Select(self.driver.find_element(By.NAME, "nCategoria"))
                categoria_select.select_by_value(dados["nCategoria"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nPreco").send_keys(dados["nPreco"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nEstoque").send_keys(dados["nEstoque"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nDescricao").send_keys(dados["nDescricao"])
                time.sleep(1)  # Pausa para você conseguir olhar a tela pronta

                nome_print = self.tirar_screenshot(f"cadastro_produto_{i+1}.png")

                # Clica em salvar
                self.driver.find_element(By.ID, "btnSalvar").click()

                time.sleep(3)  # Aguarda 3 segundos para o PHP salvar no banco

                url_atual = self.driver.current_url.lower()
                codigo_fonte = self.driver.page_source.lower()
                if "msg=sucesso" in url_atual or "cadastrado com sucesso" in codigo_fonte:
                    status = "Sucesso"

                # Espera mais um pouco antes de iniciar o próximo laço (produto seguinte)
                time.sleep(2)

            except Exception as e:
                print(f"✗ Erro no processo do produto {dados['nProduto']}: {e}")
                nome_print = self.tirar_screenshot(f"cadastro_produto_erro_{i+1}.png")

            self.resultados_testes.append({
                "id": i + 1,
                "nome": dados["nProduto"],
                "categoria": dados["nCategoria"],
                "status": status,
                "screenshot": nome_print
            })

        caminho_report = self.gerar_relatorio_html()
        self.driver.quit()

        print(f"\n✅ Testes finalizados! Relatório gerado em: {caminho_report}")
        webbrowser.open('file://' + os.path.realpath(caminho_report))


if __name__ == "__main__":
    print("--- SISTEMA DE AUTOMAÇÃO GRÃO & MASSA (PRODUTOS) ---")

    cpf_login = input("Digite o CPF do funcionário para login: ")
    senha_login = input("Digite a senha: ")

    try:
        qtd = int(input("Quantos produtos você deseja cadastrar automaticamente? "))
        if qtd > 0:
            URL_LOGIN = "http://localhost:8080/padaria_grao_massa/index.php"
            URL_CADASTRO = "http://localhost:8080/padaria_grao_massa/Cadastrar_Produto.php"

            teste = TesteAutomatizadoProduto(url_login=URL_LOGIN, url_cadastro=URL_CADASTRO)
            teste.realizar_login(cpf_login, senha_login)
            teste.executar_teste_completo(qtd)
        else:
            print("Quantidade inválida.")
    except ValueError:
        print("Por favor, digite apenas números inteiros.")