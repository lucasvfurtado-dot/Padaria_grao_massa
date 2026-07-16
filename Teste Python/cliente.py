from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time
import random
import os
import webbrowser

class TesteAutomatizadoCliente:
    def __init__(self, url_login, url_cadastro):
        self.url_login = url_login
        self.url_cadastro = url_cadastro
        self.diretorio_teste = "TesteCadastroClientes"
        
        # Cria a pasta se não existir
        if not os.path.exists(self.diretorio_teste):
            os.makedirs(self.diretorio_teste)
            
        # Lista para armazenar resultados do relatório
        self.resultados_testes = []

        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")
        
        self.driver = webdriver.Chrome(options=chrome_options)
        self.wait = WebDriverWait(self.driver, 10)
        
        print("✓ Ambiente preparado e pasta 'TesteCadastroClientes' verificada!")

    def realizar_login(self, cpf, senha):
        print("\n🔑 Realizando login no sistema...")
        try:
            self.driver.get(self.url_login)
            time.sleep(1) # Pausa para ver a tela de login
            
            self.wait.until(EC.presence_of_element_located((By.NAME, "cpf"))).send_keys(cpf)
            time.sleep(1)
            
            self.driver.find_element(By.NAME, "senha").send_keys(senha)
            time.sleep(1)
            
            self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
            time.sleep(3) # Aguarda 3 segundos após o login
            print("✓ Login efetuado com sucesso!")
        except Exception as e:
            print(f"✗ Erro ao tentar fazer o login: {e}")
            self.driver.quit()
            exit()

    def gerar_dados_aleatorios(self):
        nomes = ["Mercado Silva", "Padaria Pão Quente", "Restaurante Saboroso", "Carlos Almeida", 
                 "Mariana Costa", "Cafeteria Central", "Lanchonete Express", "Roberto Dias"]
        cidades = ["São Paulo", "Joinville", "Curitiba", "Belo Horizonte", "Rio de Janeiro"]
        
        nome = random.choice(nomes)
        nome_email = nome.lower().replace(' ', '.').replace('ã', 'a').replace('ç', 'c').replace('ó', 'o')
        
        return {
            "nNome": nome,
            "nCpfCnpj": f"{random.randint(100, 999)}.{random.randint(100, 999)}.{random.randint(100, 999)}-{random.randint(10, 99)}",
            "nEmail": f"contato@{nome_email}.com.br",
            "nTelefone": f"(47) 9{random.randint(1000, 9999)}-{random.randint(1000, 9999)}",
            "nCep": random.choice(["01001-000", "89201-000", "80010-000", "30140-071", "20040-002"]),
            "nLogradouro": f"Rua Teste Automatizado",
            "nNumero": str(random.randint(10, 9999)),
            "nComplemento": f"Sala {random.randint(1, 20)}",
            "nBairro": "Centro",
            "nCidade": random.choice(cidades),
            "nUf": random.choice(["SP", "SC", "PR", "MG"])
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
                <h1>Relatório de Automação: Cadastro de Clientes</h1>
                <div class="summary">
                    <div class="card"><h3>Total Cadastros</h3><h2>{len(self.resultados_testes)}</h2></div>
                    <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                    <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente / Razão Social</th>
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
                time.sleep(1) # Espera a página carregar
                
                # Preenchendo os dados devagar
                self.wait.until(EC.presence_of_element_located((By.NAME, "nNome"))).send_keys(dados["nNome"])
                time.sleep(pausa_digitacao)
                
                self.driver.find_element(By.NAME, "nCpfCnpj").send_keys(dados["nCpfCnpj"])
                time.sleep(pausa_digitacao)
                
                self.driver.find_element(By.NAME, "nEmail").send_keys(dados["nEmail"])
                time.sleep(pausa_digitacao)
                
                self.driver.find_element(By.NAME, "nTelefone").send_keys(dados["nTelefone"])
                time.sleep(pausa_digitacao)
                
                self.driver.find_element(By.NAME, "nCep").send_keys(dados["nCep"])
                time.sleep(2) # Pausa maior pro CEP caso puxe endereço automático
                
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
                time.sleep(1) # Pausa para você conseguir olhar a tela pronta
                
                nome_print = self.tirar_screenshot(f"cadastro_cliente_{i+1}.png")
                
                # Clica em salvar
                self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
                
                time.sleep(3) # Aguarda 3 segundos para o PHP salvar no banco
                
                codigo_fonte = self.driver.page_source.lower()
                if "msg=sucesso" in self.driver.current_url.lower() or "cadastrado com sucesso" in codigo_fonte or "cadastrar cliente" in codigo_fonte:
                    status = "Sucesso"
                
                # Espera mais um pouco antes de iniciar o próximo laço (cliente seguinte)
                time.sleep(2) 
                
            except Exception as e:
                print(f"✗ Erro no processo do cliente {dados['nNome']}: {e}")
                nome_print = self.tirar_screenshot(f"cadastro_cliente_erro_{i+1}.png")
            
            self.resultados_testes.append({
                "id": i+1,
                "nome": dados["nNome"],
                "status": status,
                "screenshot": nome_print
            })

        caminho_report = self.gerar_relatorio_html()
        self.driver.quit()
        
        print(f"\n✅ Testes finalizados! Relatório gerado em: {caminho_report}")
        webbrowser.open('file://' + os.path.realpath(caminho_report))


if __name__ == "__main__":
    print("--- SISTEMA DE AUTOMAÇÃO GRÃO & MASSA ---")
    
    cpf_login = input("Digite o CPF do funcionário para login: ")
    senha_login = input("Digite a senha: ")
    
    try:
        qtd = int(input("Quantos clientes você deseja cadastrar automaticamente? "))
        if qtd > 0:
            URL_LOGIN = "http://localhost:8080/Github/Padaria_grao_massa/index.php" 
            URL_CADASTRO = "http://localhost:8080/Github/Padaria_grao_massa/Cadastrar_Cliente.php"
            
            teste = TesteAutomatizadoCliente(url_login=URL_LOGIN, url_cadastro=URL_CADASTRO)
            teste.realizar_login(cpf_login, senha_login)
            teste.executar_teste_completo(qtd)
        else:
            print("Quantidade inválida.")
    except ValueError:
        print("Por favor, digite apenas números inteiros.")