from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time
import random
import os
import webbrowser

class TesteAutomatizadoFuncionario:
    def __init__(self, url_login, url_cadastro):
        self.url_login = url_login
        self.url_cadastro = url_cadastro
        self.diretorio_teste = "TesteCadastroFuncionarios"

        # Cria a pasta se não existir
        if not os.path.exists(self.diretorio_teste):
            os.makedirs(self.diretorio_teste)

        # Lista para armazenar resultados do relatório
        self.resultados_testes = []

        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")

        self.driver = webdriver.Chrome(options=chrome_options)
        self.wait = WebDriverWait(self.driver, 10)

        print("✓ Ambiente preparado e pasta 'TesteCadastroFuncionarios' verificada!")

    def realizar_login(self, cpf, senha):
        print("\n🔑 Realizando login no sistema...")
        try:
            self.driver.get(self.url_login)
            time.sleep(1) 

            self.wait.until(EC.presence_of_element_located((By.NAME, "cpf"))).send_keys(cpf)
            time.sleep(1)

            self.driver.find_element(By.NAME, "senha").send_keys(senha)
            time.sleep(1)

            self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
            time.sleep(3) 
            print("✓ Login efetuado com sucesso!")
        except Exception as e:
            print(f"✗ Erro ao tentar fazer o login: {e}")
            self.driver.quit()
            exit()

    def gerar_dados_aleatorios(self):
        nomes = ["Ana", "Carlos", "Beatriz", "Daniel", "Eduarda", "Felipe", "Gabriela", "Henrique"]
        sobrenomes = ["Silva", "Souza", "Costa", "Santos", "Oliveira", "Pereira", "Rodrigues", "Almeida"]
        
        nome_completo = f"{random.choice(nomes)} {random.choice(sobrenomes)}"
        cpf = f"{random.randint(100, 999)}.{random.randint(100, 999)}.{random.randint(100, 999)}-{random.randint(10, 99)}"
        cargos = ["Padeiro", "Caixa", "Gerente"]
        cargo = random.choice(cargos)
        email = f"{nome_completo.replace(' ', '').lower()}@email.com"
        telefone = f"({random.randint(10, 99)}) 9{random.randint(1000, 9999)}-{random.randint(1000, 9999)}"
        senha = f"Senha{random.randint(123, 999)}"
        
        # Dados de endereço genéricos
        cep = f"{random.randint(10000, 99999)}-{random.randint(100, 999)}"
        logradouro = "Rua das Automações"
        numero = str(random.randint(1, 999))
        complemento = "Apto " + str(random.randint(1, 100))
        bairro = "Centro"
        cidade = "São Paulo"
        uf = "SP"

        return {
            "nNomeCompleto": nome_completo,
            "nCpf": cpf,
            "nCargo": cargo,
            "nEmail": email,
            "nTelefone": telefone,
            "nSenha": senha,
            "nCep": cep,
            "nLogradouro": logradouro,
            "nNumero": numero,
            "nComplemento": complemento,
            "nBairro": bairro,
            "nCidade": cidade,
            "nUf": uf
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
            <title>Dashboard de Testes - Grão & Massa (Funcionários)</title>
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
                <h1>Relatório de Automação: Cadastro de Funcionários</h1>
                <div class="summary">
                    <div class="card"><h3>Total Cadastros</h3><h2>{len(self.resultados_testes)}</h2></div>
                    <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                    <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>CPF</th>
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
                    <td>{r['cargo']}</td>
                    <td>{r['cpf']}</td>
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
        pausa_digitacao = 0.5

        for i in range(quantidade):
            print(f"\n🚀 Iniciando cadastro {i+1} de {quantidade}...")
            dados = self.gerar_dados_aleatorios()
            status = "Falha"
            nome_print = ""

            try:
                self.driver.get(self.url_cadastro)
                time.sleep(1) 

                # Dados Pessoais e Profissionais
                self.wait.until(EC.presence_of_element_located((By.NAME, "nNomeCompleto"))).send_keys(dados["nNomeCompleto"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nCpf").send_keys(dados["nCpf"])
                time.sleep(pausa_digitacao)

                categoria_select = Select(self.driver.find_element(By.NAME, "nCargo"))
                categoria_select.select_by_value(dados["nCargo"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nEmail").send_keys(dados["nEmail"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nTelefone").send_keys(dados["nTelefone"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nSenha").send_keys(dados["nSenha"])
                time.sleep(pausa_digitacao)

                # Endereço
                self.driver.find_element(By.NAME, "nCep").send_keys(dados["nCep"])
                time.sleep(1) # Aguarda um pouco caso a função buscarEnderecoPorCEP seja disparada no blur
                
                self.driver.find_element(By.NAME, "nLogradouro").send_keys(dados["nLogradouro"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nNumero").send_keys(dados["nNumero"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nComplemento").send_keys(dados["nComplemento"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nBairro").send_keys(dados["nBairro"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nCidade").send_keys(dados["nCidade"])
                time.sleep(pausa_digitacao)

                self.driver.find_element(By.NAME, "nUf").send_keys(dados["nUf"])
                time.sleep(1) 

                nome_print = self.tirar_screenshot(f"cadastro_funcionario_{i+1}.png")

                # Clica em salvar usando XPATH pois não há ID no botão
                self.driver.find_element(By.XPATH, "//button[@type='submit']").click()

                time.sleep(3) 

                url_atual = self.driver.current_url.lower()
                codigo_fonte = self.driver.page_source.lower()
                if "msg=sucesso" in url_atual or "cadastrado" in codigo_fonte:
                    status = "Sucesso"

                time.sleep(2)

            except Exception as e:
                print(f"✗ Erro no processo do funcionário {dados['nNomeCompleto']}: {e}")
                nome_print = self.tirar_screenshot(f"cadastro_funcionario_erro_{i+1}.png")

            self.resultados_testes.append({
                "id": i + 1,
                "nome": dados["nNomeCompleto"],
                "cargo": dados["nCargo"],
                "cpf": dados["nCpf"],
                "status": status,
                "screenshot": nome_print
            })

        caminho_report = self.gerar_relatorio_html()
        self.driver.quit()

        print(f"\n✅ Testes finalizados! Relatório gerado em: {caminho_report}")
        webbrowser.open('file://' + os.path.realpath(caminho_report))


if __name__ == "__main__":
    print("--- SISTEMA DE AUTOMAÇÃO GRÃO & MASSA (FUNCIONÁRIOS) ---")

    cpf_login = input("Digite o CPF do funcionário para login: ")
    senha_login = input("Digite a senha: ")

    try:
        qtd = int(input("Quantos funcionários você deseja cadastrar automaticamente? "))
        if qtd > 0:
            URL_LOGIN = "http://localhost:8080/padaria_grao_massa/login.php"
            URL_CADASTRO = "http://localhost:8080/padaria_grao_massa/Cadastrar_Funcionario.php"

            teste = TesteAutomatizadoFuncionario(url_login=URL_LOGIN, url_cadastro=URL_CADASTRO)
            teste.realizar_login(cpf_login, senha_login)
            teste.executar_teste_completo(qtd)
        else:
            print("Quantidade inválida.")
    except ValueError:
        print("Por favor, digite apenas números inteiros.")