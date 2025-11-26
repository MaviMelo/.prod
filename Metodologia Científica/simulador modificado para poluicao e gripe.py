import random
from abc import ABC, abstractmethod

# ---------- CLASSES DE ESTADO ----------

class State(ABC):
    @abstractmethod
    def handle(self, individual):
        pass

class ProbabilisticStateHandler(State):
    transitions = []
    possibilities = []

    def handle(self, individual):
        chance = random.random()
        cumulative = 0
        for state, probability in zip(self.transitions, self.possibilities):
            cumulative += probability
            if chance < cumulative:
                individual.next_state = state()
                break

class Healthy(State):
    def handle(self, individual):
        if not isinstance(individual.next_state, Sick):
            individual.next_state = self

class Sick(ProbabilisticStateHandler):
    def __init__(self):
        self.transitions = [Dead, Immune, Sick]
        self.possibilities = [0.1, 0.6, 0.3]  # 10% morto, 60% imune, 30% continua doente

class Dead(State):
    def handle(self, individual):
        individual.next_state = self

class Immune(ProbabilisticStateHandler):
    def __init__(self):
        self.transitions = [Healthy, Immune]
        self.possibilities = [0.2, 0.8]  # 20% saudável, 80% permanece imune

# ---------- CLASSE DO INDIVÍDUO ----------

class Individual:
    def __init__(self, id, state, vacinado=False):
        self.id = id
        self.state = state
        self.next_state = state
        self.vacinado = vacinado
        self.relations = []

    def add_relation(self, other):
        if other not in self.relations:
            self.relations.append(other)
            other.relations.append(self)

# ---------- CLASSE DE SIMULAÇÃO ----------

class Simulation:
    def __init__(self, population_size, infection_factor, generations, vacinados):
        self.population_size = population_size
        self.infection_factor = infection_factor
        self.generations = generations
        self.vacinados = vacinados
        self.population = self.createPopulation(population_size)

    def createPopulation(self, num_individuals):
        population = []
        vacinados_ids = random.sample(range(num_individuals), self.vacinados)
        for i in range(num_individuals):
            vacinado = i in vacinados_ids
            population.append(Individual(i + 1, Healthy(), vacinado=vacinado))

        # Criar relações aleatórias
        for i in range(num_individuals):
            for j in range(i + 1, num_individuals):
                if random.random() < 0.2:
                    population[i].add_relation(population[j])

        # Um indivíduo inicial doente
        initial = random.choice(population)
        initial.state = Sick()
        initial.next_state = Sick()

        return population

    def spreadVirus(self):
        for individual in self.population:
            if isinstance(individual.state, Sick):
                for friend in individual.relations:
                    self.tryToInfect(friend, individual)

    def tryToInfect(self, friend, individual):
        if isinstance(friend.state, Healthy) and isinstance(friend.next_state, Healthy):
            chance = self.infection_factor
            if friend.vacinado:
                chance *= 0.1  # vacinado tem 90% menos chance de infecção
            if random.random() < chance:
                friend.next_state = Sick()

    def run(self):
        for _ in range(self.generations):
            self.spreadVirus()
            for individual in self.population:
                individual.state.handle(individual)
            for individual in self.population:
                individual.state = individual.next_state

    def countIndividuals(self, state):
        return sum(isinstance(ind.state, state) for ind in self.population)

# ---------- MENU PRINCIPAL ----------

def menu():
    print("\n--- SIMULAÇÃO DE SAÚDE E POLUIÇÃO ATMOSFÉRICA ---")
    print("Este programa estima hospitalizações em função da poluição (CO² em ppm) e vacinação.\n")

    quantidade = int(input("Quantidade de pessoas: "))
    vezes = int(input("Quantidade de vezes que a simulação vai rodar: "))
    vacinadas = int(input("Quantidade de pessoas vacinadas: "))

    # Cálculo de pessoas não vacinadas
    nao_vacinadas = quantidade - vacinadas

    print("\n--- MEDIDOR DE POLUIÇÃO ---")
    print("Considera que até 800 ppm de CO₂ não é considerado poluição (faixa aceitável).")
    print("A partir de 801 ppm, é considerado nível de poluição prejudicial à saúde.")
    print("Pessoas não vacinadas têm chance maior de hospitalização em ambientes poluídos.")
    print("O número de hospitalizações é calculado de forma probabilística.")

    ppm = float(input("Informe a quantidade de CO² (em Partes por Milhão - ppm): "))

    if ppm <= 800:
        print(f"\nNível de CO² = {ppm} ppm → ✅ Ar limpo, sem poluição.")
        poluido = False
    else:
        print(f"\nNível de CO² = {ppm} ppm → ⚠️ POLUIÇÃO detectada! (acima de 800 ppm)")
        poluido = True

    # Executa simulação
    infect_factor = 1 if poluido else 0.2  # mais poluição = mais risco
    generations = 10
    hospitalizados_total = 0

    for i in range(vezes):
        sim = Simulation(quantidade, infect_factor, generations, vacinadas)
        sim.run()
        mortos = sim.countIndividuals(Dead)

        # Hospitalização: não vacinados têm maior chance quando há poluição
        if poluido:
            hospitalizados = int(nao_vacinadas * random.uniform(0.2, 0.5))
        else:
            hospitalizados = int(nao_vacinadas * random.uniform(0.01, 0.05))

        hospitalizados_total += hospitalizados

    media_hospitalizados = hospitalizados_total / vezes

    print("\n--- RESULTADOS ---")
    print(f"População total: {quantidade}")
    print(f"Pessoas vacinadas: {vacinadas}")
    print(f"Pessoas não vacinadas: {nao_vacinadas}")
    print(f"CO² atmosférico: {ppm} ppm")
    print(f"Nível de poluição: {'Sim' if poluido else 'Não'}")
    print(f"Quantidade média de hospitalizações: {int(media_hospitalizados)}")
    print(f"Quantidade média de mortes (estimada): {mortos}")
    print("\nSimulação concluída com sucesso!\n")

# ---------- EXECUÇÃO ----------
if __name__ == "__main__":
    menu()
