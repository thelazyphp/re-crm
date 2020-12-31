import Base from './Base'

export default class Bot extends Base {
    get schema () {
        return {
            id: undefined,
            active: false,
            running: false,
            run_at: undefined
        }
    }
}
